<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\TeachingAssignment;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Untuk upload file

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments.
     */
    public function index(Request $request)
    {
        $assignments = Assignment::with(['teachingAssignment.schoolClass', 'teachingAssignment.subject', 'teachingAssignment.teacher', 'assignedBy'])
            ->orderBy('due_date', 'desc');

        // Batasi akses berdasarkan peran
        if (auth()->user()->hasRole('guru')) {
            $assignments->where('assigned_by_user_id', auth()->id()) // Hanya tugas yang diberikan guru ini
                ->orWhereHas('teachingAssignment', function ($query) { // Atau tugas yang terkait dengan TA guru ini
                    $query->where('teacher_id', auth()->id());
                });
        } elseif (auth()->user()->hasRole('siswa') && auth()->user()->student) {
            $studentId = auth()->user()->student->id;
            $classId = auth()->user()->student->school_class_id;
            $assignments->whereHas('teachingAssignment', function ($query) use ($classId) {
                $query->where('school_class_id', $classId); // Hanya tugas untuk kelas siswa ini
            });
            // Siswa juga bisa melihat status submission mereka
            $assignments->withExists('submissions', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            });
        } elseif (auth()->user()->hasRole('orang_tua') && auth()->user()->children->isNotEmpty()) {
            $childClassIds = auth()->user()->children->pluck('school_class_id')->unique()->toArray();
            $assignments->whereHas('teachingAssignment', function ($query) use ($childClassIds) {
                $query->whereIn('school_class_id', $childClassIds);
            });
        }

        // Filters (can be expanded)
        if ($request->has('class_id') && auth()->user()->hasRole('admin_sekolah')) {
            $assignments->whereHas('teachingAssignment', function ($q) use ($request) {
                $q->where('school_class_id', $request->input('class_id'));
            });
        }

        $assignments = $assignments->paginate(10);
        $classes = SchoolClass::orderBy('name')->get(); // For filter dropdown

        return view('admin.assignments.index', compact('assignments', 'classes'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        // Hanya Admin dan Guru yang bisa membuat tugas
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $teachingAssignments = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->get();
        $academicYears = $this->getAcademicYears(); // Helper dari ScheduleController, bisa dipindah ke AppServiceProvider

        return view('admin.assignments.create', compact('teachingAssignments', 'academicYears'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && !auth()->user()->hasRole('guru'), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'teaching_assignment_id' => [
                'required',
                'exists:teaching_assignments,id',
                // Pastikan TA ini milik guru yang login jika bukan admin
                Rule::exists('teaching_assignments', 'id')->where(function ($query) {
                    if (!auth()->user()->hasRole('admin_sekolah')) {
                        $query->where('teacher_id', auth()->id());
                    }
                })
            ],
            'due_date' => ['required', 'date', 'after_or_equal:now'],
            'max_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assignment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar', 'max:10240'], // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('assignment_file')) {
            $filePath = $request->file('assignment_file')->store('public/assignments');
            $filePath = str_replace('public/', 'storage/', $filePath); // Store as public path
        }

        Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
            'file_path' => $filePath,
            'assigned_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Tugas/Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified assignment (for both teacher/admin to see submissions, and student to submit).
     */
    public function show(Assignment $assignment)
    {
        $user = auth()->user();
        $assignment->load(['teachingAssignment.schoolClass', 'teachingAssignment.subject', 'teachingAssignment.teacher', 'assignedBy']);

        // Teacher/Admin view: list submissions
        if ($user->hasRole('admin_sekolah') || ($user->hasRole('guru') && $assignment->teachingAssignment->teacher_id === $user->id)) {
            $submissions = $assignment->submissions()->with('student.user')->orderBy('submission_date', 'asc')->paginate(10);
            return view('admin.assignments.show_teacher', compact('assignment', 'submissions'));
        }
        // Student view: show own submission status, or form to submit
        elseif ($user->hasRole('siswa') && $user->student && $assignment->teachingAssignment->school_class_id === $user->student->school_class_id) {
            $submission = $assignment->submissions()->where('student_id', $user->student->id)->first();
            return view('admin.assignments.show_student', compact('assignment', 'submission'));
        }

        // Default: 403 Forbidden
        abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat tugas ini.');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment)
    {
        // Hanya Admin atau guru yang membuat tugas yang bisa edit
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $assignment->assigned_by_user_id), 403);

        $teachingAssignments = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('academic_year', 'desc')
            ->get();
        $academicYears = $this->getAcademicYears();

        return view('admin.assignments.edit', compact('assignment', 'teachingAssignments', 'academicYears'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $assignment->assigned_by_user_id), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'teaching_assignment_id' => [
                'required',
                'exists:teaching_assignments,id',
                Rule::exists('teaching_assignments', 'id')->where(function ($query) {
                    if (!auth()->user()->hasRole('admin_sekolah')) {
                        $query->where('teacher_id', auth()->id());
                    }
                })
            ],
            'due_date' => ['required', 'date', 'after_or_equal:now'],
            'max_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assignment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar', 'max:10240'], // Max 10MB
            'remove_file' => ['boolean'], // Checkbox to remove file
        ]);

        $filePath = $assignment->file_path; // Keep old path by default
        if ($request->hasFile('assignment_file')) {
            // Delete old file if exists
            if ($filePath && Storage::disk('public')->exists(str_replace('storage/', 'public/', $filePath))) {
                Storage::disk('public')->delete(str_replace('storage/', 'public/', $filePath));
            }
            $filePath = $request->file('assignment_file')->store('public/assignments');
            $filePath = str_replace('public/', 'storage/', $filePath);
        } elseif ($request->boolean('remove_file') && $filePath) {
            if (Storage::disk('public')->exists(str_replace('storage/', 'public/', $filePath))) {
                Storage::disk('public')->delete(str_replace('storage/', 'public/', $filePath));
            }
            $filePath = null;
        }

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
            'file_path' => $filePath,
            'assigned_by_user_id' => auth()->id(), // Guru yang update
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Tugas/Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(Assignment $assignment)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $assignment->assigned_by_user_id), 403);

        // Hapus file terkait jika ada
        if ($assignment->file_path && Storage::disk('public')->exists(str_replace('storage/', 'public/', $assignment->file_path))) {
            Storage::disk('public')->delete(str_replace('storage/', 'public/', $assignment->file_path));
        }
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Tugas/Materi berhasil dihapus.');
    }

    /**
     * Method to handle submission upload by student.
     */
    public function submitAssignment(Request $request, Assignment $assignment)
    {
        abort_if(!auth()->user()->hasRole('siswa') || !auth()->user()->student, 403);

        $student = auth()->user()->student;

        // Cek apakah siswa sudah pernah submit
        if ($assignment->submissions()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah mengumpulkan tugas ini. Silakan hubungi guru jika perlu perubahan.');
        }

        // Cek apakah tugas sudah melewati batas waktu (overdue)
        if ($assignment->isOverdue()) {
            return redirect()->back()->with('error', 'Tugas ini sudah melewati batas waktu pengumpulan.');
        }

        $request->validate([
            'submission_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,mp4', 'max:20480'], // Max 20MB
            'submission_content' => ['nullable', 'string', 'max:5000'],
        ], [
            'submission_file.max' => 'Ukuran file pengumpulan maksimal 20 MB.',
            'submission_content.max' => 'Konten pengumpulan maksimal 5000 karakter.',
        ]);

        if (!$request->hasFile('submission_file') && !$request->filled('submission_content')) {
            return redirect()->back()->withErrors(['submission_file' => 'Anda harus mengupload file atau mengisi konten pengumpulan.'])->withInput();
        }

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('public/submissions');
            $filePath = str_replace('public/', 'storage/', $filePath);
        }

        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'submission_date' => now(),
            'file_path' => $filePath,
            'content' => $request->submission_content,
            'score' => null, // Belum dinilai
            'feedback' => null,
            'graded_by_user_id' => null, // Belum dinilai
        ]);

        return redirect()->back()->with('success', 'Pengumpulan tugas berhasil!');
    }

    /**
     * Show form to grade a submission by teacher.
     */
    public function showSubmissionForGrading(Submission $submission)
    {
        // Hanya Admin atau guru yang mengassign tugas ini yang bisa menilai
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $submission->assignment->assigned_by_user_id), 403);
        $submission->load('student.user', 'assignment.teachingAssignment.subject', 'assignment.teachingAssignment.teacher');
        return view('admin.submissions.grade', compact('submission'));
    }

    /**
     * Grade a submission.
     */
    public function gradeSubmission(Request $request, Submission $submission)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah') && (auth()->id() !== $submission->assignment->assigned_by_user_id), 403);

        $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:' . $submission->assignment->max_score],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'graded_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.assignments.show', $submission->assignment->id)->with('success', 'Nilai tugas berhasil disimpan.');
    }

    /**
     * Helper method to get a list of academic years for dropdowns.
     * (Duplicated for simplicity, ideally in a helper or service provider)
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