<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\TeachingAssignment;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index(Request $request)
    {
        // Filter by academic year
        $academicYear = $request->input('academic_year', Carbon::now()->year . '/' . (Carbon::now()->year + 1));

        $schedules = Schedule::with(['schoolClass', 'teachingAssignment.subject', 'teachingAssignment.teacher'])
            ->where('schedules.academic_year', $academicYear)
            ->orderBy(
                SchoolClass::select('name')
                    ->whereColumn('classes.id', 'schedules.school_class_id')
                    ->limit(1)
            )
            ->orderByRaw("FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->join('classes', 'schedules.school_class_id', '=', 'classes.id')
            ->select('schedules.*')
            ->orderBy('classes.name');

        // --- BATASI DATA UNTUK GURU ---
        if (auth()->user()->hasRole('guru')) {
            $schedules->whereHas('teachingAssignment', function ($query) {
                $query->where('teacher_id', auth()->id());
            });
        }
        // --- BATASI DATA UNTUK SISWA ---
        if (auth()->user()->hasRole('siswa') && auth()->user()->student) {
            $schedules->where('schedules.school_class_id', auth()->user()->student->school_class_id);
        }
        // --- FILTER BERDASARKAN KELAS DARI PARAMETER URL (MISAL DARI DASHBOARD ADMIN) ---
        if ($request->has('class_id') && (auth()->user()->hasRole('admin_sekolah') || auth()->user()->hasRole('guru'))) {
            $schedules->where('schedules.school_class_id', $request->input('class_id'));
        }

        $schedules = $schedules->paginate(10);
        $classes = SchoolClass::orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'classes', 'academicYear'));
    }
    /**
     * Show the form for creating a new schedule entry.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        $classes = SchoolClass::orderBy('name')->get();
        $teachingAssignments = TeachingAssignment::with(['subject', 'teacher', 'schoolClass'])
            ->orderBy('academic_year', 'desc')
            // ->orderBy('schoolClass.name') // Pastikan baris ini sudah dikoreksi/dihapus sesuai diskusi sebelumnya
            ->get();


        $academicYears = $this->getAcademicYears();
        return view('admin.schedules.create', compact('classes', 'teachingAssignments', 'academicYears'));
    }
    /**
     * Store a newly created schedule entry in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:classes,id'],
            'teaching_assignment_id' => [
                'required',
                'exists:teaching_assignments,id',
                Rule::exists('teaching_assignments', 'id')->where(function ($query) use ($request) {
                    return $query->where('school_class_id', $request->school_class_id);
                }),
            ],
            'day_of_week' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room_number' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
        ], [
            'school_class_id.required' => 'Kolom Kelas wajib diisi.',
            'school_class_id.exists' => 'Kelas yang dipilih tidak valid.',
            'teaching_assignment_id.required' => 'Kolom Mata Pelajaran & Guru wajib diisi.',
            'teaching_assignment_id.exists' => 'Mata Pelajaran & Guru tidak valid atau tidak sesuai dengan kelas.',
            'day_of_week.required' => 'Kolom Hari wajib diisi.',
            'day_of_week.in' => 'Hari yang dipilih tidak valid.',
            'start_time.required' => 'Kolom Waktu Mulai wajib diisi.',
            'start_time.date_format' => 'Format Waktu Mulai harus format jam:menit (HH:mm).',
            'end_time.required' => 'Kolom Waktu Selesai wajib diisi.',
            'end_time.date_format' => 'Format Waktu Selesai tidak valid.',
            'end_time.after' => 'Waktu Selesai harus lebih lambat dari Waktu Mulai.',
            'academic_year.required' => 'Tahun ajaran wajib diisi.',
            'academic_year.max' => 'Tahun ajaran terlalu panjang.',
        ]);

        // Format waktu
        $start = Carbon::parse($request->start_time)->format('H:i:s');
        $end = Carbon::parse($request->end_time)->format('H:i:s');

        $teacherId = TeachingAssignment::find($request->teaching_assignment_id)?->teacher_id;

        // 💥 CEK TABRAKAN JADWAL KELAS
        $classConflict = Schedule::where('school_class_id', $request->school_class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })->exists();

        if ($classConflict) {
            return back()->withErrors(['school_class_id' => '⚠️ Jadwal bentrok: kelas ini sudah punya pelajaran lain di waktu tersebut.'])->withInput();
        }

        // 💥 CEK TABRAKAN JADWAL GURU
        $teacherConflict = Schedule::whereHas('teachingAssignment', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teaching_assignment_id' => '⚠️ Jadwal bentrok: guru ini sudah mengajar kelas lain di waktu tersebut.'])->withInput();
        }

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', '✅ Jadwal berhasil ditambahkan!');
    }




    /**
     * Show the form for editing the specified schedule entry.
     */
    public function edit(Schedule $schedule)
    {

        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $classes = SchoolClass::orderBy('name')->get();
        $teachingAssignments = TeachingAssignment::with(['subject', 'teacher', 'schoolClass'])
            ->orderBy('academic_year', 'desc')
            ->get();
        $academicYears = $this->getAcademicYears();

        return view('admin.schedules.edit', compact('schedule', 'classes', 'teachingAssignments', 'academicYears'));
    }

    /**
     * Update the specified schedule entry in storage.
     */
    /**
     * Update the specified schedule entry in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:classes,id'],
            'teaching_assignment_id' => [
                'required',
                'exists:teaching_assignments,id',
                Rule::exists('teaching_assignments', 'id')->where(function ($query) use ($request) {
                    return $query->where('school_class_id', $request->school_class_id);
                }),
            ],
            'day_of_week' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room_number' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
        ], [
            'school_class_id.required' => 'Kolom Kelas wajib diisi.',
            'school_class_id.exists' => 'Kelas yang dipilih tidak valid.',
            'teaching_assignment_id.required' => 'Kolom Mata Pelajaran & Guru wajib diisi.',
            'teaching_assignment_id.exists' => 'Mata Pelajaran & Guru tidak valid atau tidak sesuai dengan kelas.',
            'day_of_week.required' => 'Kolom Hari wajib diisi.',
            'day_of_week.in' => 'Hari yang dipilih tidak valid.',
            'start_time.required' => 'Kolom Waktu Mulai wajib diisi.',
            'start_time.date_format' => 'Format Waktu Mulai harus format jam:menit (HH:mm).',
            'end_time.required' => 'Kolom Waktu Selesai wajib diisi.',
            'end_time.date_format' => 'Format Waktu Selesai tidak valid.',
            'end_time.after' => 'Waktu Selesai harus lebih lambat dari Waktu Mulai.',
            'academic_year.required' => 'Tahun ajaran wajib diisi.',
            'academic_year.max' => 'Tahun ajaran terlalu panjang.',
        ]);

        $start = Carbon::parse($request->start_time)->format('H:i:s');
        $end = Carbon::parse($request->end_time)->format('H:i:s');
        $teacherId = TeachingAssignment::find($request->teaching_assignment_id)?->teacher_id;

        // 💥 CEK TABRAKAN JADWAL KELAS (kecuali diri sendiri)
        $classConflict = Schedule::where('id', '!=', $schedule->id)
            ->where('school_class_id', $request->school_class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })->exists();

        if ($classConflict) {
            return back()->withErrors(['school_class_id' => '⚠️ Jadwal tabrakan: kelas ini sudah ada pelajaran lain di waktu tersebut.'])->withInput();
        }

        // 💥 CEK TABRAKAN JADWAL GURU (kecuali diri sendiri)
        $teacherConflict = Schedule::where('id', '!=', $schedule->id)
            ->whereHas('teachingAssignment', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teaching_assignment_id' => '⚠️ Jadwal bentrok: guru ini sedang mengajar kelas lain di waktu tersebut.'])->withInput();
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', '✅ Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified schedule entry from storage.
     */
    public function destroy(Schedule $schedule)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /**
     * Helper method to get a list of academic years for dropdowns.
     */
    protected function getAcademicYears()
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = -2; $i <= 2; $i++) { // Current year +/- 2 years
            $years[] = ($currentYear + $i) . '/' . ($currentYear + $i + 1);
        }
        return $years;
    }
}
