<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Untuk tanggal

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function index(Request $request)
    {
        $attendances = Attendance::with(['student.user', 'teachingAssignment.schoolClass', 'teachingAssignment.subject', 'recordedByTeacher'])
            ->orderBy('date', 'desc')
            ->join('teaching_assignments as ta_attendances', 'attendances.teaching_assignment_id', '=', 'ta_attendances.id')
            ->join('classes as c_attendances', 'ta_attendances.school_class_id', '=', 'c_attendances.id')
            ->join('users as u_attendances', 'attendances.student_id', '=', 'u_attendances.id')
            ->select('attendances.*')
            ->orderBy('c_attendances.name')
            ->orderBy('u_attendances.name');

        // --- BATASI DATA UNTUK GURU ---
        if (auth()->user()->hasRole('guru')) {
            $attendances->where('ta_attendances.teacher_id', auth()->id());
        }
        // --- BATASI DATA UNTUK SISWA ---
        if (auth()->user()->hasRole('siswa') && auth()->user()->student) {
            $attendances->where('attendances.student_id', auth()->user()->student->id);
        }
        // --- FILTER BERDASARKAN GURU DARI PARAMETER URL (MISAL DARI DASHBOARD ADMIN) ---
        if ($request->has('teacher_id') && auth()->user()->hasRole('admin_sekolah')) {
            $attendances->where('ta_attendances.teacher_id', $request->input('teacher_id'));
        }
        // --- FILTER BERDASARKAN SISWA DARI PARAMETER URL (MISAL DARI DASHBOARD SISWA/ORTU) ---
        if ($request->has('student_id') && (auth()->user()->hasRole('admin_sekolah') || auth()->user()->hasRole('orang_tua'))) {
            $attendances->where('attendances.student_id', $request->input('student_id'));
        }

        // Query filter contoh (bisa dikembangkan)
        if ($request->has('date')) {
            $attendances->whereDate('date', $request->input('date'));
        }
        if ($request->has('class_id')) {
            $attendances->whereHas('teachingAssignment', function ($q) use ($request) {
                $q->where('school_class_id', $request->input('class_id'));
            });
        }

        $attendances = $attendances->paginate(10);
        return view('admin.attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $teachingAssignments = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->get();
        // Students will be filtered by chosen teaching assignment in the form (via JS or Livewire)
        // For now, load all, or none and rely on selection process
        // Perbaikan ada di sini:
        $students = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id') // <-- JOIN TABEL USERS
            ->select('students.*') // <-- PILIH KOLOM ASLI
            ->orderBy('users.name') // <-- ORDER BERDASARKAN user.name
            ->get();
        $recordedByTeacher = auth()->user(); // Guru yang sedang login

        return view('admin.attendances.create', compact('teachingAssignments', 'students', 'recordedByTeacher'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teaching_assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', 'string', 'in:Hadir,Izin,Sakit,Alpha'],
            'notes' => ['nullable', 'string'],
            // recorded_by_teacher_id diambil dari user yang login
            Rule::unique('attendances')->where(function ($query) use ($request) {
                return $query->where('student_id', $request->student_id)
                    ->where('teaching_assignment_id', $request->teaching_assignment_id)
                    ->where('date', $request->date);
            }),
        ]);

        if (!auth()->user()->hasRole('guru') && !auth()->user()->hasRole('admin_sekolah')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mencatat absensi.');
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'date' => $request->date,
            'status' => $request->status,
            'notes' => $request->notes,
            'recorded_by_teacher_id' => auth()->id(),
        ]);

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit(Attendance $attendance)
    {
        $teachingAssignments = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->get();
        $students = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id') // <-- JOIN TABEL USERS
            ->select('students.*') // <-- PILIH KOLOM ASLI
            ->orderBy('users.name') // <-- ORDER BERDASARKAN user.name
            ->get();

        $recordedByTeacher = auth()->user();

        return view('admin.attendances.edit', compact('attendance', 'teachingAssignments', 'students', 'recordedByTeacher'));
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teaching_assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', 'string', 'in:Hadir,Izin,Sakit,Alpha'],
            'notes' => ['nullable', 'string'],
            Rule::unique('attendances')->ignore($attendance->id)->where(function ($query) use ($request) {
                return $query->where('student_id', $request->student_id)
                    ->where('teaching_assignment_id', $request->teaching_assignment_id)
                    ->where('date', $request->date);
            }),
        ]);

        if (!auth()->user()->hasRole('guru') && !auth()->user()->hasRole('admin_sekolah')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memperbarui absensi.');
        }

        // Hanya guru yang mencatat (atau admin) yang bisa update
        if (!auth()->user()->hasRole('admin_sekolah') && $attendance->recorded_by_teacher_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda hanya bisa mengubah absensi yang Anda masukkan sendiri.');
        }

        $attendance->update([
            'student_id' => $request->student_id,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'date' => $request->date,
            'status' => $request->status,
            'notes' => $request->notes,
            // recorded_by_teacher_id tidak diubah
        ]);

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroy(Attendance $attendance)
    {
        if (!auth()->user()->hasRole('admin_sekolah')) { // Hanya admin yang bisa menghapus total
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus absensi.');
        }
        $attendance->delete();
        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
