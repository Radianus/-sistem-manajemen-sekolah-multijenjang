<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Announcement;
use App\Models\HeroSlider;
use App\Models\Gallery; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function home()
    {
        $latestNews = News::published()->latest()->take(3)->get();
        $importantAnnouncements = Announcement::active()
            ->targetedTo(['all', 'siswa', 'orang_tua'])
            ->latest()
            ->take(3)
            ->get();
        $sliders = HeroSlider::where('is_active', true)->orderBy('order')->get();
        $latestGallery = Gallery::latest()->take(4)->get(); // <-- TAMBAHKAN INI

        return view('web.home', compact('latestNews', 'importantAnnouncements', 'sliders', 'latestGallery')); // Tambah latestGallery
    }

    /**
     * Display a listing of published news.
     */
    public function newsIndex()
    {
        $news = News::published()->latest()->paginate(10);
        return view('web.news.index', compact('news'));
    }

    /**
     * Display the specified news article.
     */
    public function newsShow($slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        $news->load('author');
        return view('web.news.show', compact('news'));
    }

    /**
     * Display the public gallery page.
     */
    public function galleryIndex() // <-- TAMBAHKAN METODE INI
    {
        $gallery = Gallery::latest()->paginate(16); // Ambil semua gambar galeri terbaru
        return view('web.gallery.index', compact('gallery'));
    }
}