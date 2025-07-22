<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\TeachingAssignment;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOptions = ['Hadir', 'Izin', 'Sakit', 'Alpha'];

        // Pastikan ada data di Student, TeachingAssignment, User (guru)
        $student = Student::inRandomOrder()->first();
        $assignment = TeachingAssignment::inRandomOrder()->first();
        $recordedByTeacher = User::role('guru')->inRandomOrder()->first() ?? User::role('admin_sekolah')->inRandomOrder()->first();

        // Default values for relations, will be overridden in seeder if needed
        return [
            'student_id' => $student ? $student->id : Student::factory(),
            'teaching_assignment_id' => $assignment ? $assignment->id : TeachingAssignment::factory(),
            'date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement($statusOptions),
            'notes' => fake()->optional()->sentence(),
            'recorded_by_teacher_id' => $recordedByTeacher ? $recordedByTeacher->id : User::factory()->role('guru'),
        ];
    }
}
