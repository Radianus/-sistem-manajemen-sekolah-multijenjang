<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User; // Untuk guru
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * Display a listing of the grades.
     */

    public function index(Request $request)
    {
        $grades = Grade::with(['student.user', 'teachingAssignment.schoolClass', 'teachingAssignment.subject', 'teachingAssignment.teacher', 'gradedByTeacher'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->join('teaching_assignments as ta_grades', 'grades.teaching_assignment_id', '=', 'ta_grades.id')
            ->join('classes as c_grades', 'ta_grades.school_class_id', '=', 'c_grades.id')
            ->join('subjects as s_grades', 'ta_grades.subject_id', '=', 's_grades.id')
            ->select('grades.*')
            ->orderBy('c_grades.name')
            ->orderBy('s_grades.name');

        // --- BATASI DATA UNTUK GURU ---
        if (auth()->user()->hasRole('guru')) {
            $grades->where('ta_grades.teacher_id', auth()->id());
        }
        // --- BATASI DATA UNTUK SISWA ---
        if (auth()->user()->hasRole('siswa') && auth()->user()->student) {
            $grades->where('grades.student_id', auth()->user()->student->id);
        }
        // --- FILTER BERDASARKAN GURU DARI PARAMETER URL (MISAL DARI DASHBOARD ADMIN) ---
        if ($request->has('teacher_id') && auth()->user()->hasRole('admin_sekolah')) {
            $grades->where('ta_grades.teacher_id', $request->input('teacher_id'));
        }
        // --- FILTER BERDASARKAN SISWA DARI PARAMETER URL (MISAL DARI DASHBOARD SISWA/ORTU) ---
        if ($request->has('student_id') && (auth()->user()->hasRole('admin_sekolah') || auth()->user()->hasRole('orang_tua'))) {
            $grades->where('grades.student_id', $request->input('student_id'));
        }
        // (Untuk orang tua, jika Anda mengimplementasikan relasi orang tua-siswa, Anda akan memfilter berdasarkan children mereka)

        // Query filter contoh (bisa dikembangkan lebih lanjut)
        if ($request->has('search')) {
            $search = $request->input('search');
            $grades->whereHas('student.user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('teachingAssignment.subject', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $grades = $grades->paginate(10);
        return view('admin.grades.index', compact('grades'));
    }


    /**
     * Show the form for creating a new grade.
     */
    public function create()
    {
        // Ambil penugasan mengajar sesuai guru (sudah ada)
        $teachingAssignmentsQuery = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('subject_id');

        if (auth()->user()->hasRole('guru')) {
            $teachingAssignmentsQuery->where('teacher_id', auth()->id());
        }
        $teachingAssignments = $teachingAssignmentsQuery->get();

        // Ambil siswa sesuai guru (sudah ada)
        $studentsQuery = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*')
            ->orderBy('users.name');

        if (auth()->user()->hasRole('guru')) {
            $classesTaughtByTeacher = TeachingAssignment::where('teacher_id', auth()->id())
                ->pluck('school_class_id')
                ->unique()
                ->toArray();
            if (empty($classesTaughtByTeacher)) {
                $studentsQuery->whereRaw('0=1');
            } else {
                $studentsQuery->whereIn('school_class_id', $classesTaughtByTeacher);
            }
        }

        $students = $studentsQuery->get();

        $loggedInTeacher = auth()->user();

        // Pastikan students dikirim sebagai JSON yang bisa dibaca Alpine.js
        return view('admin.grades.create', compact('teachingAssignments', 'students', 'loggedInTeacher'));
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teaching_assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'], // Nilai 0-100
            'grade_type' => ['required', 'string', 'in:Tugas,Ulangan Harian,UTS,UAS,Nilai Akhir'],
            'semester' => ['required', 'string', 'in:Ganjil,Genap'],
            'academic_year' => ['required', 'string', 'max:255'],
            // Graded_by_teacher_id akan diambil dari user yang login
            'notes' => ['nullable', 'string'],
        ]);

        // Pastikan guru yang login adalah guru atau admin
        if (!auth()->user()->hasRole('guru') && !auth()->user()->hasRole('admin_sekolah')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memasukkan nilai.');
        }

        $grade = Grade::create([
            'student_id' => $request->student_id,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'score' => $request->score,
            'grade_type' => $request->grade_type,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'graded_by_teacher_id' => auth()->id(), // Ambil ID guru yang sedang login
            'notes' => $request->notes,
        ]);
        // --- BUAT NOTIFIKASI UNTUK NILAI BARU ---
        $student = Student::with('user', 'parents')->find($request->student_id);
        $subjectName = $grade->teachingAssignment->subject->name ?? 'Mata Pelajaran';

        // Notifikasi untuk Siswa
        if ($student && $student->user) {
            Notification::create([
                'user_id' => $student->user->id,
                'type' => 'new_grade',
                'title' => 'Nilai Baru: ' . $subjectName,
                'message' => 'Nilai baru untuk ' . $subjectName . ' (' . $grade->grade_type . ') telah diunggah: ' . $grade->grade_value,
                'link' => route('admin.grades.index', ['student_id' => $student->id]),
            ]);
        }

        // Notifikasi untuk Orang Tua
        if ($student && $student->parents->isNotEmpty()) {
            foreach ($student->parents as $parentUser) {
                Notification::create([
                    'user_id' => $parentUser->id,
                    'type' => 'new_grade',
                    'title' => 'Nilai Baru Anak: ' . $student->user->name,
                    'message' => 'Nilai baru untuk anak Anda, ' . $student->user->name . ' (' . $subjectName . ' - ' . $grade->grade_type . '): ' . $grade->grade_value,
                    'link' => route('admin.grades.index', ['student_id' => $student->id]),
                ]);
            }
        }
        // --- AKHIR NOTIFIKASI ---

        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified grade.
     */
    public function edit(Grade $grade)
    {
        // Ambil penugasan mengajar sesuai guru (sudah ada)
        $teachingAssignmentsQuery = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('subject_id');

        if (auth()->user()->hasRole('guru')) {
            $teachingAssignmentsQuery->where('teacher_id', auth()->id());
        }
        $teachingAssignments = $teachingAssignmentsQuery->get();

        // Ambil siswa sesuai guru (sudah ada)
        $studentsQuery = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*')
            ->orderBy('users.name');

        if (auth()->user()->hasRole('guru')) {
            $classesTaughtByTeacher = TeachingAssignment::where('teacher_id', auth()->id())
                ->pluck('school_class_id')
                ->unique()
                ->toArray();
            if (empty($classesTaughtByTeacher)) {
                $studentsQuery->whereRaw('0=1');
            } else {
                $studentsQuery->whereIn('school_class_id', $classesTaughtByTeacher);
            }
        }

        $students = $studentsQuery->get();

        $loggedInTeacher = auth()->user();

        // Pastikan students dikirim sebagai JSON yang bisa dibaca Alpine.js
        return view('admin.grades.edit', compact('grade', 'teachingAssignments', 'students', 'loggedInTeacher'));
    }

    /**
     * Update the specified grade in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teaching_assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'grade_type' => ['required', 'string', 'in:Tugas,Ulangan Harian,UTS,UAS,Nilai Akhir'],
            'semester' => ['required', 'string', 'in:Ganjil,Genap'],
            'academic_year' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        if (!auth()->user()->hasRole('guru') && !auth()->user()->hasRole('admin_sekolah')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memperbarui nilai.');
        }

        // Pastikan hanya guru yang bersangkutan (atau admin) yang bisa mengupdate nilainya
        // Jika Anda ingin guru lain atau admin bisa mengubah, hapus pengecekan ini
        if (!auth()->user()->hasRole('admin_sekolah') && $grade->graded_by_teacher_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda hanya bisa mengubah nilai yang Anda masukkan sendiri.');
        }


        $grade->update([
            'student_id' => $request->student_id,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'score' => $request->score,
            'grade_type' => $request->grade_type,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            // graded_by_teacher_id tidak diubah saat update, tetap guru yang pertama kali input
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Remove the specified grade from storage.
     */
    public function destroy(Grade $grade)
    {
        if (!auth()->user()->hasRole('admin_sekolah')) { // Hanya admin yang bisa menghapus total
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus nilai.');
        }

        $grade->delete();
        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
