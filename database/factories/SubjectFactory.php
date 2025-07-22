<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Contoh sederhana, SubjectSeeder akan mengisi data lebih spesifik
        return [
            'name' => fake()->unique()->word() . ' ' . fake()->unique()->word(),
            'code' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'level' => fake()->randomElement(['SD', 'SMP', 'SMA', 'SMK', 'Umum']),
        ];
    }
}
