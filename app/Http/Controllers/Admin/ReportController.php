<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student; // Untuk mendapatkan data siswa
use App\Models\SchoolClass; // Untuk dropdown filter kelas
use App\Models\TeachingAssignment; // Untuk mendapatkan mata pelajaran dari TA
use App\Models\Subject; // Untuk mendapatkan daftar mata pelajaran
use App\Models\Grade; // Untuk nilai
use App\Models\Attendance; // Untuk absensi
use Carbon\Carbon; // Untuk tahun ajaran
use App\Models\User; // Untuk guru, wali kelas
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    /**
     * Show the report card form/filters.
     */
    public function showReportCardFilterForm(Request $request)
    {
        // Hanya Admin dan Guru (Wali Kelas) yang bisa akses
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $classes = SchoolClass::orderBy('name')->get();
        $academicYears = $this->getAcademicYears(); // Ambil dari ScheduleController atau AppServiceProvider
        $students = collect([]); // Awalnya kosong, akan diisi via AJAX jika perlu, atau semua siswa

        if (auth()->user()->hasRole('guru')) {
            // Jika guru, tampilkan hanya siswa dari kelas yang dia ajar sebagai wali kelas
            $homeroomClasses = SchoolClass::where('homeroom_teacher_id', auth()->id())->pluck('id');
            if ($homeroomClasses->isNotEmpty()) {
                $students = Student::with('user')
                    ->join('users', 'students.user_id', '=', 'users.id') // <-- JOIN USERS
                    ->select('students.*') // <-- SELECT STUDENTS KOLOM ASLI
                    ->whereIn('school_class_id', $homeroomClasses)
                    ->orderBy('users.name') // <-- ORDER BERDASARKAN users.name
                    ->get();
            }
        } else {
            $students = Student::with('user')
                ->join('users', 'students.user_id', '=', 'users.id') // <-- JOIN USERS
                ->select('students.*') // <-- SELECT STUDENTS KOLOM ASLI
                ->orderBy('users.name') // <-- ORDER BERDASARKAN users.name
                ->get();
        }

        return view('reports.report_card_filter', compact('classes', 'academicYears', 'students'));
    }

    /**
     * Generate and display the student report card.
     */
    public function generateReportCard(Request $request)
    {
        // Hanya Admin dan Guru (Wali Kelas) yang bisa generate
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', Rule::in(['Ganjil', 'Genap'])],
        ]);

        $student = Student::with(['user', 'schoolClass.homeroomTeacher', 'schoolClass.teachingAssignments.subject', 'schoolClass.teachingAssignments.teacher'])->find($request->student_id);

        // Pastikan guru hanya bisa generate untuk siswa di kelasnya sebagai wali kelas
        if (auth()->user()->hasRole('guru')) {
            $isHomeroomTeacher = SchoolClass::where('id', $student->school_class_id)
                ->where('homeroom_teacher_id', auth()->id())
                ->exists();
            abort_if(!$isHomeroomTeacher && !auth()->user()->hasRole('admin_sekolah'), 403, 'Akses Ditolak. Anda bukan wali kelas siswa ini.');
        }

        if (!$student) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }
        if (!$student->schoolClass) {
            return redirect()->back()->with('error', 'Siswa belum terdaftar di kelas manapun.');
        }

        $academicYear = $request->academic_year;
        $semester = $request->semester;

        // Ambil semua mata pelajaran yang diajarkan di kelas siswa ini pada tahun ajaran tersebut
        $teachingAssignmentsInClass = TeachingAssignment::with(['subject', 'teacher'])
            ->where('school_class_id', $student->school_class_id)
            ->where('academic_year', $academicYear)
            ->get();

        $reportSubjects = [];
        foreach ($teachingAssignmentsInClass as $ta) {
            $subjectGrades = Grade::where('student_id', $student->id)
                ->where('teaching_assignment_id', $ta->id)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->get();

            $reportSubjects[] = [
                'subject_name' => $ta->subject->name ?? 'N/A',
                'teacher_name' => $ta->teacher->name ?? 'N/A',
                'grades' => $subjectGrades,
                'average_score' => $subjectGrades->avg('score'),
            ];
        }

        // Ringkasan Absensi
        $attendanceSummary = Attendance::where('student_id', $student->id)
            ->whereHas('teachingAssignment', function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })
            // Ini bisa diperluas untuk filter semester jika absensi per semester
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $totalDays = array_sum($attendanceSummary); // Total hari yang tercatat absensinya
        if ($totalDays == 0) { // Avoid division by zero
            $attendanceSummary['Hadir_percent'] = 0;
            $attendanceSummary['Izin_percent'] = 0;
            $attendanceSummary['Sakit_percent'] = 0;
            $attendanceSummary['Alpha_percent'] = 0;
        } else {
            $attendanceSummary['Hadir_percent'] = ($attendanceSummary['Hadir'] ?? 0) / $totalDays * 100;
            $attendanceSummary['Izin_percent'] = ($attendanceSummary['Izin'] ?? 0) / $totalDays * 100;
            $attendanceSummary['Sakit_percent'] = ($attendanceSummary['Sakit'] ?? 0) / $totalDays * 100;
            $attendanceSummary['Alpha_percent'] = ($attendanceSummary['Alpha'] ?? 0) / $totalDays * 100;
        }

        // Catatan Wali Kelas (Placeholder, but can be added via new module/field later)
        $homeroomTeacherComment = "Kerja bagus di semester ini! Terus tingkatkan."; // Default comment

        return view('reports.report_card', compact('student', 'academicYear', 'semester', 'reportSubjects', 'attendanceSummary', 'homeroomTeacherComment'));
    }

    /**
     * Show the grade summary report filter form.
     */
    public function showGradeSummaryFilterForm(Request $request)
    {
        // Hanya Admin dan Guru yang bisa akses
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);
        $classes = SchoolClass::orderBy('name')->get();
        $academicYears = $this->getAcademicYears();
        $students = collect([]);
        if (auth()->user()->hasRole('guru')) {
            $homeroomClasses = SchoolClass::where('homeroom_teacher_id', auth()->id())->pluck('id');
            if ($homeroomClasses->isNotEmpty()) {
                $students = Student::with('user')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->select('students.*')
                    ->whereIn('school_class_id', $homeroomClasses)
                    ->orderBy('users.name')
                    ->get();
            }
        } else { // Admin
            $students = Student::with('user')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->select('students.*')
                ->orderBy('users.name')
                ->get();
        }

        return view('reports.grade_summary_filter', compact('classes', 'academicYears', 'students'));
    }

    /**
     * Generate and display the grade summary report.
     */
    public function generateGradeSummary(Request $request)
    {
        // Hanya Admin dan Guru yang bisa generate
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', Rule::in(['Ganjil', 'Genap'])],
        ]);

        $classId = $request->class_id;
        $academicYear = $request->academic_year;
        $semester = $request->semester;

        $studentsQuery = Student::with(['user', 'schoolClass'])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*')
            ->orderBy('users.name');

        if ($classId) {
            $studentsQuery->where('school_class_id', $classId);
        }
        // Batasi untuk guru hanya melihat kelas yang dia ajar
        if (auth()->user()->hasRole('guru')) {
            $homeroomClasses = SchoolClass::where('homeroom_teacher_id', auth()->id())->pluck('id');
            if ($homeroomClasses->isEmpty()) {
                $studentsQuery->whereRaw('0=1');
            } else {
                $studentsQuery->whereIn('school_class_id', $homeroomClasses);
            }
        }

        $students = $studentsQuery->get();

        $reportSummary = [];
        foreach ($students as $student) {
            $grades = Grade::where('student_id', $student->id)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->with('teachingAssignment.subject')
                ->get();

            $totalScore = 0;
            $totalSubjects = 0;
            $subjectsData = [];

            foreach ($grades->groupBy('teachingAssignment.subject.name') as $subjectName => $subjectGrades) {
                $finalGrade = $subjectGrades->where('grade_type', 'Nilai Akhir')->first();
                if ($finalGrade) {
                    $subjectsData[] = [
                        'subject_name' => $subjectName,
                        'score' => $finalGrade->score,
                    ];
                    $totalScore += $finalGrade->score;
                    $totalSubjects++;
                }
            }

            $averageOverall = $totalSubjects > 0 ? $totalScore / $totalSubjects : 0;

            $reportSummary[] = [
                'student_name' => $student->user->name ?? 'N/A',
                'class_name' => $student->schoolClass->name ?? 'N/A',
                'subjects_data' => $subjectsData,
                'average_overall' => $averageOverall,
            ];
        }

        // Urutkan laporan berdasarkan rata-rata atau nama siswa
        usort($reportSummary, function ($a, $b) {
            return $a['average_overall'] <=> $b['average_overall']; // Urutkan berdasarkan nilai rata-rata
        });
        $classes = SchoolClass::orderBy('name')->get();
        return view('reports.grade_summary', compact('reportSummary', 'academicYear', 'semester', 'classId', 'classes'));
    }
    /**
     * Helper method to get a list of academic years for dropdowns.
     * (This is a duplication from ScheduleController, ideally in a helper or service provider)
     */
    protected function getAcademicYears()
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = -2; $i <= 2; $i++) {
            $years[] = ($currentYear + $i) . '/' . ($currentYear + $i + 1);
        }
        return $years;
    }
}