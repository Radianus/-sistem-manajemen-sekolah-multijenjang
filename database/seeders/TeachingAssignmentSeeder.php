<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeachingAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Database\Factories\TeachingAssignmentFactory; // Import factory

class TeachingAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = User::role('guru')->get();

        if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('Tidak cukup data kelas, mata pelajaran, atau guru untuk seed teaching assignments. Pastikan seeder sebelumnya sudah dijalankan.');
            return;
        }

        $academicYear = '2025/2026';
        $seededCount = 0;

        // Coba buat kombinasi penugasan yang lebih banyak
        foreach ($classes as $class) {
            foreach ($subjects as $subject) {
                // Ambil guru secara acak
                $teacher = $teachers->random();
                if ($teacher) {
                    try {
                        TeachingAssignment::firstOrCreate(
                            [
                                'school_class_id' => $class->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher->id,
                                'academic_year' => $academicYear,
                            ],
                            [] // Tidak perlu mengisi data karena firstOrCreate akan membuat jika tidak ada
                        );
                        $seededCount++;
                    } catch (\Throwable $e) {
                        // Handle unique constraint violation or other errors
                        // $this->command->error('Error seeding TA: ' . $e->getMessage());
                    }
                }
                // Batasi jumlah penugasan yang dibuat agar tidak terlalu banyak jika terlalu banyak kombinasi
                if ($seededCount >= 50) { // Misalnya, batasi 50 penugasan
                    break 2; // Keluar dari kedua loop
                }
            }
        }
        $this->command->info('Teaching assignments seeded: ' . $seededCount);
    }
}
