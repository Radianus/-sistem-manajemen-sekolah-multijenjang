<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Announcement;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = Role::pluck('name')->toArray();
        $roles[] = 'all'; // Include 'all' option

        $publishedAt = fake()->dateTimeBetween('-1 month', '+1 week'); // Published between 1 month ago and 1 week from now
        $expiresAt = fake()->boolean(70) ? fake()->dateTimeBetween($publishedAt, '+3 months') : null; // 70% chance to expire within 3 months

        $creator = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        return [
            'title' => fake()->sentence(rand(4, 8)),
            'content' => fake()->paragraphs(rand(2, 5), true),
            'published_at' => $publishedAt,
            'expires_at' => $expiresAt,
            'created_by_user_id' => $creator ? $creator->id : User::factory()->role('admin_sekolah'), // Ensure creator exists
            'target_roles' => implode(',', fake()->randomElements($roles, rand(1, 2))), // Assign 1-2 random target roles
        ];
    }

    /**
     * Indicate that the announcement is for all roles.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forAllRoles(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'target_roles' => 'all',
            ];
        });
    }

    /**
     * Indicate that the announcement is expired.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => fake()->dateTimeBetween('-2 months', '-1 month'),
                'expires_at' => fake()->dateTimeBetween('-1 week', 'now'),
            ];
        });
    }

    /**
     * Indicate that the announcement is scheduled for future.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function future(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
                'expires_at' => null,
            ];
        });
    }
}
