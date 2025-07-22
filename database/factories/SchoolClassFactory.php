<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SchoolClass;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = ['SD', 'SMP', 'SMA', 'SMK'];
        $level = fake()->randomElement($levels);
        $gradeLevel = '';

        switch ($level) {
            case 'SD':
                $gradeLevel = fake()->numberBetween(1, 6);
                break;
            case 'SMP':
                $gradeLevel = fake()->numberBetween(7, 9);
                break;
            case 'SMA':
                $gradeLevel = fake()->numberBetween(10, 12);
                break;
            case 'SMK':
                $gradeLevel = fake()->numberBetween(10, 12);
                break;
        }

        // --- PERBAIKI BAGIAN INI ---
        // Tambahkan angka unik untuk memastikan nama kelas tidak duplikat
        $className = 'Kelas ' . $gradeLevel . ' ' . $level . ' ' . fake()->unique()->randomNumber(3);
        // ---------------------------

        return [
            'name' => $className,
            'level' => $level,
            'grade_level' => (string) $gradeLevel,
            'academic_year' => '2025/2026',
            // homeroom_teacher_id akan diisi di seeder
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (SchoolClass $schoolClass) {
            $teacher = User::role('guru')->inRandomOrder()->first();
            if ($teacher) {
                $schoolClass->homeroom_teacher_id = $teacher->id;
                $schoolClass->save();
            }
        });
    }
}
