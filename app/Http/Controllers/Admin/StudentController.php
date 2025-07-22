<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass; // Import model SchoolClass
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Illuminate\Support\Str; // Untuk generate password acak
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Support\Facades\Validator; // Untuk validasi manual

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index()
    {
        $students = Student::with(['user', 'schoolClass'])
            ->orderBy('nis')
            ->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $users = User::role('siswa')->doesntHave('student')->orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $existingUsers = User::role('siswa')->doesntHave('student')->orderBy('name')->get();
        return view('admin.students.create', compact('users', 'classes', 'existingUsers'));
    }
    public function storeUserForStudent(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap akun wajib diisi.',
            'email.required' => 'Email akun wajib diisi.',
            'email.email' => 'Format email akun tidak valid.',
            'email.unique' => 'Email akun ini sudah terdaftar.',
            'password.required' => 'Password akun wajib diisi.',
            'password.confirmed' => 'Konfirmasi password akun tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'must_change_password' => true,
        ]);
        $user->assignRole('siswa');

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna siswa berhasil dibuat!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'default_password' => $request->password,
        ]);
    }
    /**
     * Store a newly created student in storage.
     */

    public function store(Request $request)
    {
        // Validasi dasar untuk field siswa
        $studentValidations = [
            'nis' => ['required', 'string', 'max:255', 'unique:students'],
            'nisn' => ['nullable', 'string', 'max:255', 'unique:students,nisn'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'school_class_id' => ['required', 'exists:classes,id'],
        ];

        // Validasi berdasarkan mode pembuatan user (dari hidden field 'user_creation_mode')
        $userMode = $request->input('user_creation_mode'); // 'new' atau 'existing'

        if ($userMode === 'new') { // Mode: Buat Akun Pengguna Baru
            $request->validate(array_merge($studentValidations, [
                'new_user_name' => ['required', 'string', 'max:255'],
                'new_user_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'new_user_password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]), [
                'new_user_name.required' => 'Nama lengkap akun wajib diisi.',
                'new_user_email.required' => 'Email akun wajib diisi.',
                'new_user_email.email' => 'Format email akun tidak valid.',
                'new_user_email.unique' => 'Email akun ini sudah terdaftar.',
                'new_user_password.required' => 'Password akun wajib diisi.',
                'new_user_password.confirmed' => 'Konfirmasi password akun tidak cocok.',
            ]);
        } else { // Mode: Pilih Akun Pengguna Yang Sudah Ada
            $request->validate(array_merge($studentValidations, [
                'user_id' => [ // ini field dari dropdown
                    'required',
                    'exists:users,id',
                    // Pastikan user belum memiliki profil siswa
                    Rule::unique('students', 'user_id')->where(function ($query) {
                        return $query->where('user_id', request('user_id'));
                    }),
                    // Pastikan user memiliki peran 'siswa'
                    Rule::exists('model_has_roles', 'model_id')->where(function ($query) {
                        return $query->where('role_id', Role::where('name', 'siswa')->first()->id ?? 0);
                    }),
                ],
            ]), [
                'user_id.required' => 'Akun pengguna siswa wajib dipilih.',
                'user_id.exists' => 'Akun pengguna siswa tidak valid.',
                'user_id.unique' => 'Akun pengguna siswa ini sudah memiliki profil siswa.',
                'user_id.exists' => 'Akun yang dipilih tidak memiliki peran siswa atau tidak valid.',
            ]);
        }

        DB::transaction(function () use ($request, $userMode) { // Use $userMode here
            $userId = null;
            $generatedPassword = null; // To store if new user is created implicitly

            if ($userMode === 'new') {
                $user = User::create([
                    'name' => $request->new_user_name,
                    'email' => $request->new_user_email,
                    'password' => Hash::make($request->new_user_password), // Use password from form
                    'email_verified_at' => now(),
                    'must_change_password' => true, // Mark for first login password change
                ]);
                $user->assignRole('siswa');
                $userId = $user->id;
                $generatedPassword = $request->new_user_password; // Store the input password
            } else { // 'existing'
                $userId = $request->user_id;
                // Optional: Ensure existing user is marked for password change if needed
                // User::find($userId)->update(['must_change_password' => true]); 
            }

            // Create student profile
            Student::create([
                'user_id' => $userId,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'school_class_id' => $request->school_class_id,
            ]);

            // Flash password if new user was created
            if ($userMode === 'new') {
                session()->flash('default_password', $generatedPassword);
                session()->flash('user_email', $request->new_user_email);
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => ['required', 'exists:users,id', Rule::unique('students', 'user_id')],
    //         'nis' => ['required', 'string', 'max:255', 'unique:students'],
    //         'nisn' => ['nullable', 'string', 'max:255', 'unique:students'],
    //         'gender' => ['required', 'in:Laki-laki,Perempuan'],
    //         'date_of_birth' => ['nullable', 'date'],
    //         'address' => ['nullable', 'string'],
    //         'phone_number' => ['nullable', 'string', 'max:20'],
    //         'school_class_id' => ['required', 'exists:classes,id'],
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         // Perbaikan ada di sini: Masukkan user_id langsung ke array create
    //         Student::create([
    //             'user_id' => $request->user_id,
    //             'nis' => $request->nis,
    //             'nisn' => $request->nisn,
    //             'gender' => $request->gender,
    //             'date_of_birth' => $request->date_of_birth,
    //             'address' => $request->address,
    //             'phone_number' => $request->phone_number,
    //             'school_class_id' => $request->school_class_id,
    //         ]);
    //         // Tidak perlu lagi $student->user()->associate() dan $student->save() terpisah
    //     });

    //     return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    // }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $users = User::role('siswa')->orderBy('name')->get(); // Semua user siswa (termasuk yang sudah punya student)
        $classes = SchoolClass::orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'users', 'classes'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id', Rule::unique('students', 'user_id')->ignore($student->id)],
            'nis' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'nisn' => ['nullable', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'school_class_id' => ['required', 'exists:classes,id'],
        ]);

        DB::transaction(function () use ($request, $student) {
            // Update student data
            $student->update($request->except('user_id'));

            // If user_id changed, disassociate old and associate new
            if ($student->user_id != $request->user_id) {
                // Not strictly necessary to disassociate from old user, as unique user_id ensures consistency
                // But if the old user_id now needs to be available for another student, this is good.
                // However, the `unique` rule handles preventing duplicate user_id.
                $student->user()->associate(User::find($request->user_id));
                $student->save();
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            // Ketika data siswa dihapus, akun user yang terhubung juga dihapus (karena onDelete('cascade'))
            // Ini mungkin perlu disesuaikan jika ingin user tetap ada meskipun data siswanya dihapus.
            // Jika ingin user tetap ada, ubah foreign key user_id di migrasi students menjadi onDelete('set null')
            // atau hapus user secara manual jika kondisinya memungkinkan.
            $student->delete();
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
