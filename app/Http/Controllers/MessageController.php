<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Display a listing of the messages (Inbox/Sent).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->input('tab', 'inbox'); // 'inbox' or 'sent'

        if ($tab === 'sent') {
            $messages = $user->sentMessages()->with('receiver')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $messages = $user->receivedMessages()->with('sender')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('messages.index', compact('messages', 'tab'));
    }

    /**
     * Show a single message or message thread.
     */
    public function show(Message $message)
    {
        // Pastikan user adalah pengirim atau penerima pesan
        abort_if(auth()->id() !== $message->sender_id && auth()->id() !== $message->receiver_id, 403);

        // Mark as read if the user is the receiver and it's unread
        if (auth()->id() === $message->receiver_id && is_null($message->read_at)) {
            $message->markAsRead();
        }

        $replies = $message->replies()->with(['sender', 'receiver'])->orderBy('created_at', 'asc')->get();

        return view('messages.show', compact('message', 'replies'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create()
    {
        $users = User::orderBy('name')->get(); // Semua user yang bisa dikirimi pesan
        return view('messages.create', compact('users'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id', Rule::notIn([auth()->id()])], // Tidak bisa kirim ke diri sendiri
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'parent_message_id' => ['nullable', 'exists:messages,id'], // Jika ini balasan
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'parent_message_id' => $request->parent_message_id,
        ]);

        $receiverUser = User::find($request->receiver_id);
        if ($receiverUser) {
            Notification::create([
                'user_id' => $receiverUser->id,
                'type' => 'new_message',
                'title' => 'Pesan Baru Dari: ' . auth()->user()->name,
                'message' => 'Anda menerima pesan baru dengan subjek: ' . ($message->subject ?? '(Tanpa Subjek)'),
                'link' => route('messages.show', $message->id),
            ]);
        }

        return redirect()->route('messages.index', ['tab' => 'sent'])->with('success', 'Pesan berhasil dikirim!');
    }

    /**
     * Reply to a message.
     */
    public function reply(Message $message)
    {
        // Pastikan user adalah pengirim atau penerima pesan asli
        abort_if(auth()->id() !== $message->sender_id && auth()->id() !== $message->receiver_id, 403);

        $users = User::orderBy('name')->get();
        return view('messages.reply', compact('message', 'users'));
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        // User hanya bisa menghapus pesan yang mereka kirim atau terima
        abort_if(auth()->id() !== $message->sender_id && auth()->id() !== $message->receiver_id, 403);

        $message->delete(); // Ini juga akan menghapus balasan karena onDelete('cascade') di migrasi
        return redirect()->route('messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
