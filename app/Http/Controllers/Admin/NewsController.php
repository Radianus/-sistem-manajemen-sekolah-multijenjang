<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        // Hanya Admin dan Guru yang bisa mengelola berita
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $news = News::with('author')->orderBy('published_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);
        return view('admin.news.create');
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:news,title'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // Max 2MB
            'published_at' => ['nullable', 'date'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }


        News::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image_path' => $imagePath,
            'user_id' => auth()->id(),
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $news->user_id), 403);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, News $news)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $news->user_id), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('news')->ignore($news->id)],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'remove_image' => ['boolean'],
        ]);

        $imagePath = $news->image_path;

        if ($request->hasFile('image')) {
            // Hapus file lama
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Simpan file baru ke `storage/app/public/news`
            $imagePath = $request->file('image')->store('news', 'public'); // 💡 Gak perlu str_replace
        } elseif ($request->boolean('remove_image') && $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        $news->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image_path' => $imagePath,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(News $news)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $news->user_id), 403);

        if ($news->image_path && Storage::disk('public')->exists(str_replace('storage/', 'public/', $news->image_path))) {
            Storage::disk('public')->delete(str_replace('storage/', 'public/', $news->image_path));
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}