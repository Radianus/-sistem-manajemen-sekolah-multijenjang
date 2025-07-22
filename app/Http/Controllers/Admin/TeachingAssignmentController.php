<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeachingAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Rules\TeacherHasRole;
use Illuminate\Validation\Rule;

class TeachingAssignmentController extends Controller
{
    /**
     * Display a listing of the teaching assignments.
     */
    public function index()
    {
        $assignments = TeachingAssignment::with(['schoolClass', 'subject', 'teacher'])
            ->join('classes', 'teaching_assignments.school_class_id', '=', 'classes.id')
            ->select('teaching_assignments.*')
            ->orderBy('academic_year', 'desc')
            ->orderBy('classes.name')
            ->paginate(10);
        return view('admin.teaching_assignments.index', compact('assignments'));
    }


    /**
     * Show the form for creating a new teaching assignment.
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::role('guru')->orderBy('name')->get();
        return view('admin.teaching_assignments.create', compact('classes', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created teaching assignment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => [
                'required',
                new TeacherHasRole(),
                Rule::unique('teaching_assignments')->where(function ($query) use ($request) {
                    return $query->where('school_class_id', $request->school_class_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('teacher_id', $request->teacher_id)
                        ->where('academic_year', $request->academic_year);
                }),
            ],
            'academic_year' => ['required', 'string', 'max:255'],
        ]);

        TeachingAssignment::create($request->all());

        return redirect()->route('admin.teaching_assignments.index')->with('success', 'Penugasan mengajar berhasil ditambahkan.');
    }


    /**
     * Show the form for editing the specified teaching assignment.
     */
    public function edit(TeachingAssignment $assignment)
    {
        // Pastikan assignment memiliki relasi yang diperlukan
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::role('guru')->orderBy('name')->get();
        return view('admin.teaching_assignments.edit', compact('assignment', 'classes', 'subjects', 'teachers'));
    }

    /**
     * Update the specified teaching assignment in storage.
     */
    public function update(Request $request, TeachingAssignment $assignment)
    {
        $request->validate([
            'school_class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => [
                'required',
                new TeacherHasRole(),
                Rule::unique('teaching_assignments')->ignore($assignment->id)->where(function ($query) use ($request) {
                    return $query->where('school_class_id', $request->school_class_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('teacher_id', $request->teacher_id)
                        ->where('academic_year', $request->academic_year);
                }),
            ],
            'academic_year' => ['required', 'string', 'max:255'],
        ]);

        $assignment->update($request->all());

        return redirect()->route('admin.teaching_assignments.index')->with('success', 'Penugasan mengajar berhasil diperbarui.');
    }

    /**
     * Remove the specified teaching assignment from storage.
     */
    public function destroy(TeachingAssignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.teaching_assignments.index')->with('success', 'Penugasan mengajar berhasil dihapus.');
    }
}
