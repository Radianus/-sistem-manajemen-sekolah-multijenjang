<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role; // Untuk daftar peran di form

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index(Request $request)
    {
        $announcements = Announcement::with('creator')
            ->active()
            ->orderBy('published_at', 'asc')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan peran user yang login
        if (auth()->user()->hasRole('admin_sekolah')) {
            // Admin bisa melihat semua
        } elseif (auth()->user()->hasRole('guru')) {
            $announcements->targetedTo(['all', 'guru']);
        } elseif (auth()->user()->hasRole('siswa')) {
            $announcements->targetedTo(['all', 'siswa']);
        } elseif (auth()->user()->hasRole('orang_tua')) {
            $announcements->targetedTo(['all', 'orang_tua']);
        } else {
            $announcements->targetedTo('all'); // Jika ada peran lain, default ke 'all'
        }

        $announcements = $announcements->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        // Hanya Admin dan Guru yang bisa membuat pengumuman
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $roles = Role::pluck('name')->prepend('all', 'all')->toArray(); // Get all roles for target audience, add 'all' option
        return view('admin.announcements.create', compact('roles'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'target_roles' => ['nullable', 'array'],
            'target_roles.*' => ['string', Rule::in(array_merge(Role::pluck('name')->toArray(), ['all']))], // Ensure selected roles are valid or 'all'
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'created_by_user_id' => auth()->id(),
            'target_roles' => $request->target_roles ? implode(',', $request->target_roles) : null,
        ]);

        // --- BUAT NOTIFIKASI UNTUK PENGUMUMAN BARU ---
        $targetRoles = $request->target_roles;
        $usersToNotify = [];

        if (in_array('all', $targetRoles)) {
            $usersToNotify = User::all();
        } else {
            foreach ($targetRoles as $roleName) {
                if ($roleName !== 'all') {
                    $usersToNotify = array_merge($usersToNotify, User::role($roleName)->get()->all());
                }
            }
            $usersToNotify = collect($usersToNotify)->unique('id'); // Hapus duplikat jika user punya multiple roles
        }
        foreach ($usersToNotify as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_announcement',
                'title' => 'Pengumuman Baru: ' . $announcement->title,
                'message' => 'Ada pengumuman baru yang relevan untuk Anda. Klik untuk melihat detail.',
                'link' => route('admin.announcements.show', $announcement->id),
            ]);
        }


        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        // Hanya Admin yang bisa mengedit pengumuman orang lain.
        // Guru hanya bisa mengedit pengumuman yang mereka buat sendiri.
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $announcement->created_by_user_id), 403);

        $roles = Role::pluck('name')->prepend('all', 'all')->toArray();
        $selectedRoles = explode(',', $announcement->target_roles ?? ''); // Convert string back to array for form

        return view('admin.announcements.edit', compact('announcement', 'roles', 'selectedRoles'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $announcement->created_by_user_id), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'target_roles' => ['nullable', 'array'],
            'target_roles.*' => ['string', Rule::in(array_merge(Role::pluck('name')->toArray(), ['all']))],
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'target_roles' => $request->target_roles ? implode(',', $request->target_roles) : null,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Hanya Admin yang bisa menghapus pengumuman orang lain.
        // Guru hanya bisa menghapus pengumuman yang mereka buat sendiri.
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $announcement->created_by_user_id), 403);

        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
