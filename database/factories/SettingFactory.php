<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Setting;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_name' => 'Akademika Sekolah Kita',
            'school_address' => fake()->address(),
            'school_phone' => fake()->phoneNumber(),
            'school_email' => 'info@akademika.sch.id',
            'current_academic_year' => '2025/2026',
            'logo_path' => null,
        ];
    }
}
