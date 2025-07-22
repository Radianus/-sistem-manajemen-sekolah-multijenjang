<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Untuk user (orang tua)
use App\Models\Student; // Untuk siswa

class ParentStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = User::role('orang_tua')->get();
        $students = Student::all();

        if ($parents->isEmpty() || $students->isEmpty()) {
            $this->command->info('Tidak cukup user orang tua atau siswa untuk seed parent-student relationships.');
            return;
        }

        $seededCount = 0;
        foreach ($students as $student) {
            // Kaitkan setiap siswa dengan 1 atau 2 orang tua secara acak
            $numParents = rand(1, 2);
            $selectedParents = $parents->random($numParents);

            foreach ($selectedParents as $parent) {
                // Gunakan attach untuk many-to-many
                // Cek apakah relasi sudah ada sebelum attach untuk menghindari duplikasi
                if (!$parent->children()->where('student_id', $student->id)->exists()) {
                    $parent->children()->attach($student->id);
                    $seededCount++;
                }
            }
        }
        $this->command->info('Parent-Student relationships seeded: ' . $seededCount);
    }
}
