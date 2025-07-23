<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\TeachingAssignment;
use App\Models\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignmentTypes = ['Individu', 'Kelompok', 'Proyek', 'Presentasi', 'Quiz'];
        $teachingAssignment = TeachingAssignment::inRandomOrder()->first();
        $assignedBy = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        if (!$teachingAssignment || !$assignedBy) {
            // Jika tidak ada data relasi esensial, return empty array agar tidak crash.
            // Seeder akan melewatkan pembuatan assignment ini.
            return [];
        }

        return [
            'title' => fake()->sentence(rand(3, 7)),
            'assignment_type' => fake()->randomElement($assignmentTypes),
            'description' => fake()->paragraph(rand(1, 3)),
            'teaching_assignment_id' => $teachingAssignment->id,
            'due_date' => fake()->dateTimeBetween('now', '+1 month'), // Jatuh tempo dalam 1 bulan
            'max_score' => fake()->numberBetween(70, 100),
            'file_path' => null, // Default null, bisa diatur nanti jika perlu dummy file
            'assigned_by_user_id' => 2,
            'is_graded_notification_sent' => false, // Default false, akan diubah oleh command
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Assignment $assignment) {
            // Contoh: secara acak membuat beberapa tugas menjadi "terlambat"
            if (fake()->boolean(20)) { // 20% kemungkinan terlambat
                $assignment->due_date = fake()->dateTimeBetween('-1 month', 'yesterday');
                $assignment->save();
            }
        });
    }
}
