<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Announcement;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function home()
    {
        // Ambil data terbaru untuk homepage
        $latestNews = News::published()->latest()->take(3)->get();
        // Anda mungkin juga perlu mengambil pengumuman di sini
        $importantAnnouncements = Announcement::active()
            ->targetedTo(['all', 'siswa', 'orang_tua'])
            ->latest()
            ->take(3)
            ->get();

        // Tambahkan globalSettings ke compact() untuk home.blade.php
        return view('web.home', compact('latestNews', 'importantAnnouncements'));
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
}
