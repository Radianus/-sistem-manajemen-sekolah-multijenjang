<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolClass; // Pastikan menggunakan model SchoolClass
use App\Models\User;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index()
    {
        $classes = SchoolClass::with('homeroomTeacher') // Gunakan SchoolClass
            ->orderBy('academic_year', 'desc')
            ->orderBy('level')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $teachers = User::role('guru')->orderBy('name')->get();
        $levelOptions = ['SD', 'SMP', 'SMA', 'SMK']; // <-- UBAH INI
        return view('admin.classes.create', compact('teachers', 'levelOptions'));
    }
    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:classes'],
            'level' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:255'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'], // Memastikan ID guru valid
        ]);

        SchoolClass::create($request->all()); // Gunakan SchoolClass

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(SchoolClass $class)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $teachers = User::role('guru')->orderBy('name')->get();
        $levelOptions = ['SD', 'SMP', 'SMA', 'SMK']; // <-- UBAH INI
        return view('admin.classes.edit', compact('class', 'teachers', 'levelOptions'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('classes')->ignore($class->id)],
            'level' => ['nullable', 'string', Rule::in(['SD', 'SMP', 'SMA', 'SMK'])], // <-- UBAH INI
            'homeroom_teacher_id' => ['nullable', Rule::exists('users', 'id')->where(function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'guru');
                });
            })],
            'academic_year' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'string', 'max:10'],
        ]);

        $class->update($request->all());

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $class) // Gunakan SchoolClass $class
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}