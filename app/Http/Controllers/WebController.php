<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Announcement;
use App\Models\CalendarEvent;
use App\Models\HeroSlider;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home()
    {
        $latestNews = News::published()->latest()->take(3)->get();
        $importantAnnouncements = Announcement::active()
            ->targetedTo(['all', 'siswa', 'orang_tua'])
            ->latest()
            ->take(3)
            ->get();
        // Ambil slider yang aktif, urutkan berdasarkan order
        $sliders = HeroSlider::where('is_active', true)->orderBy('order')->get(); // <-- TAMBAHKAN INI

        return view('web.home', compact('latestNews', 'importantAnnouncements', 'sliders'));
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