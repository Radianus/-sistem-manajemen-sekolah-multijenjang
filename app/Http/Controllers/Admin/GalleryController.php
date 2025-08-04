<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery items.
     */
    public function index()
    {
        // Hanya Admin dan Guru yang bisa mengelola galeri
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);
        $gallery = Gallery::with('uploader')->latest()->paginate(15);
        return view('admin.galleries.index', compact('gallery'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created gallery item in storage.
     */
    /**
     * Store a newly created gallery item in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'], // Max 5MB
            'event_date' => ['nullable', 'date'],
        ]);

        // --- CARA BARU YANG BENAR ---
        $imagePath = $request->file('image')->store('gallery', 'public');
        // --------------------------

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'event_date' => $request->event_date,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Gambar galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $gallery->user_id), 403);
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $gallery->user_id), 403);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'event_date' => ['nullable', 'date'],
            'remove_image' => ['boolean'],
        ]);

        $imagePath = $gallery->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) { // Path sudah benar, tidak perlu str_replace
                Storage::disk('public')->delete($imagePath);
            }
            // --- CARA BARU YANG BENAR ---
            $imagePath = $request->file('image')->store('gallery', 'public');
            // --------------------------
        } elseif ($request->boolean('remove_image') && $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) { // Path sudah benar, tidak perlu str_replace
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'event_date' => $request->event_date,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Gambar galeri berhasil diperbarui.');
    }


    /**
     * Remove the specified gallery item from storage.
     */
    public function destroy(Gallery $gallery)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $gallery->user_id), 403);

        if ($gallery->image_path && Storage::disk('public')->exists(str_replace('storage/', 'public/', $gallery->image_path))) {
            Storage::disk('public')->delete(str_replace('storage/', 'public/', $gallery->image_path));
        }
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Gambar galeri berhasil dihapus.');
    }
}