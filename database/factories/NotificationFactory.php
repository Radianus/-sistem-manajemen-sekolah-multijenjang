<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\Schedule; // <-- TAMBAHKAN INI

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
            return [];
        }

        $types = ['new_announcement', 'new_grade', 'new_message', 'schedule_change'];
        $type = $this->faker->randomElement($types);

        $title = '';
        $message = '';
        $link = '#';

        switch ($type) {
            case 'new_announcement':
                // Pastikan ada announcement yang sudah di-seed
                $announcement = \App\Models\Announcement::inRandomOrder()->first();
                if ($announcement) {
                    $title = 'Pengumuman Baru: ' . $announcement->title;
                    $message = 'Ada pengumuman baru yang relevan untuk Anda. Klik untuk melihat detail.';
                    $link = route('admin.announcements.show', $announcement->id);
                } else {
                    $title = 'Pengumuman Baru';
                    $message = 'Ada pengumuman baru.';
                    $link = route('admin.announcements.index');
                }
                break;
            case 'new_grade':
                // Pastikan ada grade yang sudah di-seed
                $grade = \App\Models\Grade::inRandomOrder()->first();
                if ($grade && $grade->student) { // Pastikan grade dan student-nya ada
                    $title = 'Nilai Baru: ' . ($grade->teachingAssignment->subject->name ?? 'Mata Pelajaran');
                    $message = 'Nilai baru untuk ' . ($grade->teachingAssignment->subject->name ?? 'Mapel') . ' (' . $grade->grade_type . ') telah diunggah: ' . $grade->score;
                    $link = route('admin.grades.index', ['student_id' => $grade->student->id]);
                } else {
                    $title = 'Nilai Baru';
                    $message = 'Ada nilai baru.';
                    $link = route('admin.grades.index');
                }
                break;
            case 'new_message':
                // Pastikan ada message yang sudah di-seed
                $messageObj = \App\Models\Message::inRandomOrder()->first();
                if ($messageObj) {
                    $sender = $messageObj->sender->name ?? 'Seseorang';
                    $title = 'Pesan Baru Dari: ' . $sender;
                    $message = 'Anda menerima pesan baru dengan subjek: ' . ($messageObj->subject ?? '(Tanpa Subjek)');
                    $link = route('messages.show', $messageObj->id);
                } else {
                    $title = 'Pesan Baru';
                    $message = 'Anda menerima pesan baru.';
                    $link = route('messages.index');
                }
                break;
            case 'schedule_change':
                // Ambil satu jadwal secara acak yang sudah ada
                $schedule = Schedule::inRandomOrder()->first();
                if ($schedule) {
                    $title = 'Perubahan Jadwal Kelas ' . ($schedule->schoolClass->name ?? 'Anda');
                    $message = 'Ada perubahan pada jadwal pelajaran Anda. Mohon periksa kembali jadwal Anda.';
                    $link = route('admin.schedules.show', $schedule->id); // <-- UBAH KE RUTE SHOW
                } else {
                    $title = 'Perubahan Jadwal';
                    $message = 'Ada perubahan jadwal.';
                    $link = route('admin.schedules.index');
                }
                break;
        }

        return [
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'read_at' => $this->faker->boolean(60) ? $this->faker->dateTimeBetween('-2 weeks', 'now') : null,
        ];
    }
}