<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminOrGuru = User::role(['admin_sekolah', 'guru'])->exists();
        if (!$adminOrGuru) {
            $this->command->info('Tidak ada admin atau guru untuk membuat pengumuman. Lewati AnnouncementSeeder.');
            return;
        }

        // Buat 10 pengumuman umum (ditargetkan acak atau 'all')
        Announcement::factory()->count(10)->create();

        // Buat 2 pengumuman khusus untuk semua peran
        Announcement::factory()->count(2)->forAllRoles()->create([
            'title' => 'Pemberitahuan Penting untuk Semua Pengguna!',
            'content' => 'Mohon perhatian untuk semua pengguna sistem Akademika. Ada pembaruan penting yang akan dilakukan pada tanggal ' . \Carbon\Carbon::now()->addDays(5)->format('d M Y') . '.',
        ]);

        // Buat 1 pengumuman yang sudah kadaluarsa
        Announcement::factory()->expired()->create([
            'title' => 'Pengumuman Lama (Sudah Kadaluarsa)',
            'content' => 'Pengumuman ini seharusnya tidak lagi terlihat di daftar aktif.',
        ]);

        // Buat 1 pengumuman yang dijadwalkan di masa depan
        Announcement::factory()->future()->create([
            'title' => 'Pengumuman Mendatang (Terbit Minggu Depan)',
            'content' => 'Pengumuman ini akan aktif pada ' . \Carbon\Carbon::now()->addDays(7)->format('d M Y') . '.',
        ]);

        $this->command->info('Announcements seeded: ' . Announcement::count());
    }
}
