<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the calendar events.
     */
    public function index(Request $request)
    {
        // --- PERBAIKI BAGIAN INI ---
        // Pastikan month dan year adalah integer
        $currentMonth = (int) $request->input('month', Carbon::now()->month);
        $currentYear = (int) $request->input('year', Carbon::now()->year);
        // ---------------------------

        $events = CalendarEvent::with('creator')
            ->forMonthAndYear($currentMonth, $currentYear)
            ->orderBy('start_date')
            ->orderBy('start_time');

        // Filter berdasarkan peran user yang login (tetap sama)
        if (auth()->user()->hasRole('admin_sekolah')) {
            // Admin bisa melihat semua
        } elseif (auth()->user()->hasRole('guru')) {
            $events->targetedTo(['all', 'guru']);
        } elseif (auth()->user()->hasRole('siswa')) {
            $events->targetedTo(['all', 'siswa']);
        } elseif (auth()->user()->hasRole('orang_tua')) {
            $events->targetedTo(['all', 'orang_tua']);
        } else {
            $events->targetedTo('all');
        }

        $events = $events->paginate(10);

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->isoFormat('MMMM');
        }
        $years = range(Carbon::now()->subYears(2)->year, Carbon::now()->addYears(2)->year);

        return view('admin.calendar_events.index', compact('events', 'currentMonth', 'currentYear', 'months', 'years'));
    }

    /**
     * Show the form for creating a new calendar event.
     */
    public function create()
    {
        // Hanya Admin dan Guru yang bisa membuat/mengedit event
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $roles = Role::pluck('name')->prepend('all', 'all')->toArray();
        $eventTypes = ['Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah', 'Lain-lain'];

        return view('admin.calendar_events.create', compact('roles', 'eventTypes'));
    }

    /**
     * Store a newly created calendar event in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_type' => ['required', 'string', Rule::in(['Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah', 'Lain-lain'])],
            'target_roles' => ['nullable', 'array'],
            'target_roles.*' => ['string', Rule::in(array_merge(Role::pluck('name')->toArray(), ['all']))],
        ]);

        CalendarEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'event_type' => $request->event_type,
            'target_roles' => $request->target_roles ? implode(',', $request->target_roles) : null,
            'created_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.calendar_events.index')->with('success', 'Acara kalender berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified calendar event.
     */
    public function edit(CalendarEvent $calendarEvent)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $calendarEvent->created_by_user_id), 403);

        $roles = Role::pluck('name')->prepend('all', 'all')->toArray();
        $eventTypes = ['Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah', 'Lain-lain'];
        $selectedRoles = explode(',', $calendarEvent->target_roles ?? '');

        return view('admin.calendar_events.edit', compact('calendarEvent', 'roles', 'eventTypes', 'selectedRoles'));
    }

    /**
     * Update the specified calendar event in storage.
     */
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $calendarEvent->created_by_user_id), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_type' => ['required', 'string', Rule::in(['Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah', 'Lain-lain'])],
            'target_roles' => ['nullable', 'array'],
            'target_roles.*' => ['string', Rule::in(array_merge(Role::pluck('name')->toArray(), ['all']))],
        ]);

        $calendarEvent->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'event_type' => $request->event_type,
            'target_roles' => $request->target_roles ? implode(',', $request->target_roles) : null,
        ]);

        return redirect()->route('admin.calendar_events.index')->with('success', 'Acara kalender berhasil diperbarui.');
    }

    /**
     * Remove the specified calendar event from storage.
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $calendarEvent->created_by_user_id), 403);

        $calendarEvent->delete();
        return redirect()->route('admin.calendar_events.index')->with('success', 'Acara kalender berhasil dihapus.');
    }


    /**
     * Export academic calendar events to PDF.
     */
    public function exportPdf()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);


        $calendarEvents = CalendarEvent::orderBy('start_date')->get();

        $data = [
            'title' => 'Laporan Kalender Akademik',
            'date' => date('d/m/Y'),
            'calendarEvents' => $calendarEvents
        ];

        $pdf = Pdf::loadView('admin.calendar_events.pdf_export', $data);

        return $pdf->download('kalender-akademik-' . time() . '.pdf');
    }
}