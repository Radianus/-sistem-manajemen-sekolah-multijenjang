<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CalendarEvent;
use App\Models\User;
use Database\Factories\CalendarEventFactory;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersExist = User::role(['admin_sekolah', 'guru'])->exists();
        if (!$usersExist) {
            $this->command->info('Tidak ada admin atau guru untuk seed calendar events.');
            return;
        }

        // Buat 15 acara kalender dummy
        CalendarEvent::factory()->count(15)->create();

        $this->command->info('Calendar events seeded: ' . CalendarEvent::count());
    }
}