<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Database\Factories\StudentFactory; // Import StudentFactory

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentUsers = User::role('siswa')->get();
        $schoolClasses = SchoolClass::all();

        if ($studentUsers->isEmpty() || $schoolClasses->isEmpty()) {
            $this->command->info('Tidak cukup user siswa atau kelas untuk seed students. Jalankan RolesAndPermissionsSeeder dan SchoolClassSeeder terlebih dahulu.');
            return;
        }

        // Ambil ID user siswa yang belum punya data student
        $availableStudentUsers = $studentUsers->filter(function ($user) {
            return !$user->student;
        });

        // Buat student record untuk setiap available user siswa
        foreach ($availableStudentUsers as $user) {
            // Cek apakah student dengan user_id ini sudah ada untuk menghindari unique constraint error
            if (!Student::where('user_id', $user->id)->exists()) {
                Student::factory()->create([
                    'user_id' => $user->id,
                    'school_class_id' => $schoolClasses->random()->id,
                    // data lainnya akan diisi oleh factory
                ]);
            }
        }
        $this->command->info('Student records seeded.');
    }
}
