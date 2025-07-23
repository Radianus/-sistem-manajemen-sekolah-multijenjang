<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeachingAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Database\Factories\TeachingAssignmentFactory;

class TeachingAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = User::role('guru')->get(); // Ambil semua guru

        if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('Tidak cukup data kelas, mata pelajaran, atau guru untuk seed teaching assignments. Pastikan seeder sebelumnya sudah dijalankan.');
            return;
        }

        $academicYear = '2025/2026';

        // Ambil guru spesifik kita (guru@akademika.com)
        $guruSatu = User::where('email', 'guru@akademika.com')->first();
        // Pastikan guruSatu ada, jika tidak, pakai guru acak
        $targetTeacherId = $guruSatu ? $guruSatu->id : $teachers->random()->id;


        // Buat penugasan spesifik untuk Guru Satu
        // Contoh: Guru Satu mengajar Matematika di Kelas 10 IPA 1
        $classTarget = $classes->where('name', 'Kelas 10 IPA 1')->first() ?? $classes->random();
        $subjectTarget = $subjects->where('name', 'Matematika')->first() ?? $subjects->random();

        if ($classTarget && $subjectTarget) {
            TeachingAssignment::firstOrCreate(
                [
                    'school_class_id' => $classTarget->id,
                    'subject_id' => $subjectTarget->id,
                    'teacher_id' => $targetTeacherId, // Tugaskan ke Guru Satu
                    'academic_year' => $academicYear,
                ],
                []
            );
            $this->command->info("Penugasan spesifik untuk guru@akademika.com (Matematika di {$classTarget->name}) dibuat.");
        }


        // Buat penugasan lainnya menggunakan factory
        // Pastikan ada cukup banyak penugasan acak lainnya
        TeachingAssignment::factory()->count(30)->create(); // Buat 30 penugasan acak

        $this->command->info('Teaching assignments seeded.');
    }
}
