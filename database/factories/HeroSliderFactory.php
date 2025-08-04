<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage; // Pastikan ini diimport

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroSlider>
 */
class HeroSliderFactory extends Factory
{
    protected $model = HeroSlider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imagePaths = [
            'dummy/slider-20250804190626-OJRgDU.jpg',
            'dummy/slider-20250804190629-o2GtLC.jpg',
            'dummy/slider-20250804190633-BBJC4F.jpg',
            'dummy/slider-20250804190636-R81cC3.jpg',
            'dummy/slider-20250804190639-mPM1EK.jpg',
        ];
        return [
            'title' => fake()->randomElement([
                'Selamat Datang di SMK Anugerah',
                'Pendaftaran Siswa Baru Telah Dibuka',
                'SMK Anugerah: Membangun Masa Depan Cerah',
                'Bergabunglah Bersama Kami',
                'Raih Cita-Citamu Bersama SMK Anugerah',
            ]),
            'subtitle' => fake()->randomElement([
                'Mewujudkan generasi unggul dan berakhlak mulia.',
                'Pendidikan berkualitas untuk masa depan yang gemilang.',
                'Kurikulum modern dan guru profesional siap mendampingimu.',
                'Kami hadir untuk mencetak lulusan siap kerja dan berwirausaha.',
                'Temukan bakat dan kembangkan potensimu bersama kami.',
                null, // subtitle kadang bisa kosong
            ]),
            'image_path' => fake()->randomElement($imagePaths),
            'link_url' => fake()->optional(70)->url(),
            'order' => fake()->unique()->numberBetween(1, 10),
            'is_active' => fake()->boolean(90),
        ];
    }
}