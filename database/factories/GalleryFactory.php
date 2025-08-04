<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Facades\Storage; // Import Storage facade

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uploader = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        if (!$uploader) {
            return [];
        }

        // Dummy image path (placeholder, assuming you have files)
        $imagePath = 'gallery/' . fake()->uuid() . '.jpg';

        return [
            'title' => fake()->sentence(rand(3, 5)),
            'description' => fake()->optional()->paragraph(rand(1, 2)),
            'image_path' => $imagePath,
            'event_date' => fake()->optional(70)->dateTimeBetween('-1 year', 'now'),
            'user_id' => $uploader->id,
        ];
    }
}