<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genderOptions = ['Laki-laki', 'Perempuan'];
        $dob = fake()->dateTimeBetween('-18 years', '-6 years'); // Siswa usia 6-18 tahun

        return [
            'nis' => fake()->unique()->numerify('##########'), // 10 digit NIS
            'nisn' => fake()->unique()->numerify('##########'), // 10 digit NISN
            'gender' => fake()->randomElement($genderOptions),
            'date_of_birth' => $dob->format('Y-m-d'),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            // user_id dan school_class_id akan diisi di seeder
        ];
    }
}
