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
        // Path dummy untuk gambar slider (asumsi ada file di storage/app/public/hero_sliders)
        $imagePaths = [
            'hero_sliders/slider1.jpg',
            'hero_sliders/slider2.jpg',
            'hero_sliders/slider3.jpg',
        ];

        return [
            'title' => fake()->sentence(rand(3, 6)),
            'subtitle' => fake()->optional()->sentence(rand(8, 15)),
            'image_path' => fake()->randomElement($imagePaths),
            'link_url' => fake()->optional(70)->url(), // 70% kemungkinan punya link
            'order' => fake()->unique()->numberBetween(1, 10),
            'is_active' => fake()->boolean(90), // 90% kemungkinan aktif
        ];
    }
}