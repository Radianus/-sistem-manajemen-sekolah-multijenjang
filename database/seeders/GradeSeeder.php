<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeachingAssignment;
use App\Models\User;
use Database\Factories\GradeFactory; // Import factory

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $teachingAssignments = TeachingAssignment::all();
        $teachers = User::role('guru')->get();
        $admin = User::role('admin_sekolah')->first();

        if ($students->isEmpty() || $teachingAssignments->isEmpty() || ($teachers->isEmpty() && !$admin)) {
            $this->command->info('Tidak cukup data siswa, penugasan mengajar, atau guru/admin untuk seed grades. Pastikan seeder sebelumnya sudah dijalankan.');
            return;
        }

        $academicYear = '2025/2026';
        $gradeTypes = ['Tugas', 'Ulangan Harian', 'UTS', 'UAS', 'Nilai Akhir'];
        $semesters = ['Ganjil', 'Genap'];

        $seededCount = 0;
        foreach ($teachingAssignments as $ta) {
            // Ambil siswa yang terdaftar di kelas penugasan ini
            $studentsInClass = $students->where('school_class_id', $ta->school_class_id);

            if ($studentsInClass->isEmpty()) {
                continue;
            }

            // Buat nilai untuk beberapa siswa di kelas ini
            foreach ($studentsInClass->take(5) as $student) { // Ambil 5 siswa per kelas-mapel untuk seeding
                foreach ($gradeTypes as $type) {
                    $score = rand(6000, 9900) / 100;
                    $semester = $semesters[array_rand($semesters)];
                    $gradedById = $ta->teacher_id; // Biasanya guru pengajar TA

                    // Pastikan tidak ada duplikasi nilai untuk kombinasi unik
                    if (!Grade::where('student_id', $student->id)
                        ->where('teaching_assignment_id', $ta->id)
                        ->where('grade_type', $type)
                        ->where('semester', $semester)
                        ->where('academic_year', $academicYear)
                        ->exists()) {
                        Grade::factory()->create([
                            'student_id' => $student->id,
                            'teaching_assignment_id' => $ta->id,
                            'score' => $score,
                            'grade_type' => $type,
                            'semester' => $semester,
                            'academic_year' => $academicYear,
                            'graded_by_teacher_id' => $gradedById,
                        ]);
                        $seededCount++;
                    }
                }
            }
        }
        $this->command->info('Grades seeded: ' . $seededCount);
    }
}
