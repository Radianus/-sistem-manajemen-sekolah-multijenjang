<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeachingAssignment;
use App\Models\User; // Untuk gradedByTeacher

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeTypes = ['Tugas', 'Ulangan Harian', 'UTS', 'UAS', 'Nilai Akhir'];
        $semesters = ['Ganjil', 'Genap'];
        $score = fake()->numberBetween(60, 99) + fake()->randomFloat(2, 0, 0.99); // 60.00 - 99.99

        // Pastikan ada data di tabel Student, TeachingAssignment, dan User (guru/admin)
        $student = Student::inRandomOrder()->first();
        $assignment = TeachingAssignment::inRandomOrder()->first();
        $teacher = User::role('guru')->inRandomOrder()->first() ?? User::role('admin_sekolah')->inRandomOrder()->first();

        return [
            'student_id' => $student ? $student->id : Student::factory(),
            'teaching_assignment_id' => $assignment ? $assignment->id : TeachingAssignment::factory(),
            'score' => $score,
            'grade_type' => fake()->randomElement($gradeTypes),
            'semester' => fake()->randomElement($semesters),
            'academic_year' => '2025/2026',
            'graded_by_teacher_id' => $teacher ? $teacher->id : User::factory()->role('admin_sekolah'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
