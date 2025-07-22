<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        if (!$user) {
            return []; // Skip if no users exist
        }

        $types = ['new_announcement', 'new_grade', 'new_message', 'schedule_change'];
        $type = $this->faker->randomElement($types);

        $title = '';
        $message = '';
        $link = '#';

        switch ($type) {
            case 'new_announcement':
                $title = 'Pengumuman Baru: ' . $this->faker->sentence(3);
                $message = $this->faker->paragraph(1);
                $link = route('admin.announcements.index'); // Example link
                break;
            case 'new_grade':
                $title = 'Nilai Baru: ' . $this->faker->word() . ' (' . $this->faker->numberBetween(70, 100) . ')';
                $message = 'Nilai ' . $this->faker->randomElement(['tugas', 'ujian']) . ' untuk ' . $this->faker->word() . ' telah diunggah.';
                $link = route('admin.grades.index', ['student_id' => $user->student->id ?? null]); // Example link
                break;
            case 'new_message':
                $sender = User::where('id', '!=', $user->id)->inRandomOrder()->first();
                $title = 'Pesan Baru dari ' . ($sender->name ?? 'Seseorang');
                $message = 'Anda menerima pesan baru dengan subjek: ' . $this->faker->sentence(4);
                $link = route('messages.index'); // Example link
                break;
            case 'schedule_change':
                $title = 'Perubahan Jadwal Kelas ' . $this->faker->word();
                $message = 'Ada perubahan pada jadwal pelajaran Anda. Mohon periksa kembali jadwal Anda.';
                $link = route('admin.schedules.index', ['class_id' => $user->student->school_class_id ?? null]); // Example link
                break;
        }

        return [
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'read_at' => $this->faker->boolean(60) ? $this->faker->dateTimeBetween('-2 weeks', 'now') : null, // 60% chance to be read
        ];
    }
}
