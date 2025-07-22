<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects'],
            'code' => ['nullable', 'string', 'max:50', 'unique:subjects'],
            'description' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'max:255'],
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects')->ignore($subject->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('subjects')->ignore($subject->id)],
            'description' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'max:255'],
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
