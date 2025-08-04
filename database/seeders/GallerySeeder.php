<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\User;
use Database\Factories\GalleryFactory;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersExist = User::role(['admin_sekolah', 'guru'])->exists();
        if (!$usersExist) {
            $this->command->info('Tidak ada admin atau guru untuk seed gallery.');
            return;
        }

        // Buat 10 gambar dummy
        Gallery::factory()->count(7)->create();

        $this->command->info('Gallery seeded: ' . Gallery::count());
    }
}