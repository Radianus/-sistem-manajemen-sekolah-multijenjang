<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Announcement;
use App\Models\CalendarEvent;
use App\Models\HeroSlider;
use App\Models\Gallery;
use Carbon\Carbon;
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
        $latestGallery = Gallery::latest()->take(4)->get();

        return view('web.home', compact('latestNews', 'importantAnnouncements', 'sliders', 'latestGallery'));
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
    public function galleryIndex()
    {
        $gallery = Gallery::latest()->paginate(16);
        return view('web.gallery.index', compact('gallery'));
    }


    public function about()
    {
        return view('web.about');
    }

    /**
     * Display the public 'Contact' page.
     */
    public function contact()
    {
        return view('web.contact');
    }
    /**
     * Display the public academic calendar page.
     */
    public function calendarIndex()
    {
        // Ambil semua event yang aktif dan ditargetkan ke publik
        $events = CalendarEvent::activeBetween(Carbon::now()->startOfYear(), Carbon::now()->endOfYear())
            ->where(function ($query) {
                $query->targetedTo('all');
            })
            ->orderBy('start_date')
            ->paginate(15);

        $currentYear = Carbon::now()->year;
        return view('web.calendar.index', compact('events', 'currentYear'));
    }
}