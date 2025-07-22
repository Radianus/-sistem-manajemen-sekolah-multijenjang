<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TeachingAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User; // Untuk guru

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeachingAssignment>
 */
class TeachingAssignmentFactory extends Factory
{
    protected $model = TeachingAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pastikan ada data di tabel SchoolClass, Subject, dan User dengan role 'guru'
        $class = SchoolClass::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $teacher = User::role('guru')->inRandomOrder()->first();

        // Ini hanya untuk factory, pastikan data relasi ada saat seeding
        return [
            'school_class_id' => $class ? $class->id : SchoolClass::factory(),
            'subject_id' => $subject ? $subject->id : Subject::factory(),
            'teacher_id' => $teacher ? $teacher->id : User::factory()->role('guru'),
            'academic_year' => '2025/2026',
        ];
    }
}
