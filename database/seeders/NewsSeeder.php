<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Database\Factories\NewsFactory;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersExist = User::role(['admin_sekolah', 'guru'])->exists();
        if (!$usersExist) {
            $this->command->info('Tidak ada admin atau guru untuk membuat berita. Lewati NewsSeeder.');
            return;
        }

        // Buat 15 berita dummy
        News::factory()->count(15)->create();

        $this->command->info('News seeded: ' . News::count());
    }
}
