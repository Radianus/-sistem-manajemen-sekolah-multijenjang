<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(15);

        // $user->notifications()->unread()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        abort_if($notification->user_id !== Auth::id(), 403);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     */
    public function unreadCount()
    {
        $count = Auth::user()->notifications()->count();
        return response()->json(['count' => $count]);
    }


    /**
     * Mark all unread notifications for the authenticated user as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->each->markAsRead();
        return response()->json(['success' => true, 'message' => 'Semua notifikasi telah ditandai sudah dibaca.']);
    }
}
