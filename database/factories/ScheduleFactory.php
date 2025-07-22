<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\TeachingAssignment;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $academicYear = '2025/2026'; // Konsisten dengan seeder lain

        // Pastikan ada data di SchoolClass dan TeachingAssignment
        $class = SchoolClass::inRandomOrder()->first();
        $assignment = TeachingAssignment::inRandomOrder()->first();

        // Ensure random times are within school hours
        $startTime = Carbon::createFromTime(rand(7, 15), rand(0, 59), 0);
        $endTime = $startTime->copy()->addMinutes(rand(45, 90)); // Pelajaran 45-90 menit

        return [
            'school_class_id' => $class ? $class->id : SchoolClass::factory(),
            'teaching_assignment_id' => $assignment ? $assignment->id : TeachingAssignment::factory(),
            'day_of_week' => fake()->randomElement($daysOfWeek),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'room_number' => fake()->optional()->bothify('Ruang ##?'),
            'academic_year' => $academicYear,
        ];
    }
}