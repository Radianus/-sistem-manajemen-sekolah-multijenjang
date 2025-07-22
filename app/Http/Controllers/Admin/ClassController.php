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

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        // Ambil semua guru untuk pilihan wali kelas
        // Pastikan user dengan peran 'guru' sudah ada di database (dari seeder)
        $teachers = User::role('guru')->orderBy('name')->get();
        return view('admin.classes.create', compact('teachers'));
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
        $teachers = User::role('guru')->orderBy('name')->get();
        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('classes')->ignore($class->id)],
            'level' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:255'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
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
