<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->info('Tidak ada user untuk seed notifications.');
            return;
        }

        // Buat beberapa notifikasi untuk setiap user
        foreach ($users as $user) {
            Notification::factory()->count(rand(3, 7))->create([
                'user_id' => $user->id,
            ]);
        }
        $this->command->info('Notifications seeded for ' . $users->count() . ' users.');
    }
}
