<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(5, 10));
        $author = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        if (!$author) {
            return [];
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(rand(3, 8), true),
            'image_path' => null, // Default null, can be set for dummy image later
            'user_id' => $author->id,
            'published_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-1 month', 'now') : null, // 70% published
        ];
    }
}
