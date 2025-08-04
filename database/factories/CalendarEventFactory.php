<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CalendarEvent;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    protected $model = CalendarEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = ['Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah', 'Lain-lain'];
        $roles = Role::pluck('name')->toArray();
        $roles[] = 'all'; // Include 'all' option

        $creator = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        if (!$creator) {
            return [];
        }

        $startDate = fake()->dateTimeBetween('-1 month', '+3 months');
        $endDate = fake()->boolean(40)
            ? Carbon::instance(clone $startDate)->addDays(fake()->numberBetween(1, 7))
            : null;
        $startTime = fake()->boolean(60) ? fake()->time('H:i:s') : null; // 60% chance of having a start time
        $endTime = $startTime ? Carbon::parse($startTime)->addMinutes(fake()->numberBetween(30, 120))->format('H:i:s') : null;

        return [
            'title' => fake()->sentence(rand(3, 8)),
            'description' => fake()->optional()->paragraph(rand(1, 3)),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => fake()->optional()->city() . ' - Ruang ' . fake()->numerify('###'),
            'event_type' => fake()->randomElement($eventTypes),
            'target_roles' => implode(',', fake()->randomElements($roles, rand(1, 2))),
            'created_by_user_id' => $creator->id,
        ];
    }
}