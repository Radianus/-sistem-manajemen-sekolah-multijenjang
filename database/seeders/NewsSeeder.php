<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Database\Factories\NewsFactory;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $usersExist = User::role(['admin_sekolah', 'guru'])->exists();
    //     if (!$usersExist) {
    //         $this->command->info('Tidak ada admin atau guru untuk membuat berita. Lewati NewsSeeder.');
    //         return;
    //     }

    //     // Buat 15 berita dummy
    //     News::factory()->count(9)->create();

    //     $this->command->info('News seeded: ' . News::count());
    // }
    public function run(): void
    {
        $author = User::role(['admin_sekolah', 'guru'])->inRandomOrder()->first();

        if (!$author) {
            $this->command->info('Tidak ada admin atau guru untuk membuat berita. Lewati NewsSeeder.');
            return;
        }

        $data = [
            ['title' => 'Siswa SMK Anugerah Juara Lomba LKS Tingkat Kota'],
            ['title' => 'Kegiatan Donor Darah di Sekolah Disambut Antusias'],
            ['title' => 'Pelatihan Digital Marketing Bagi Guru dan Siswa'],
            ['title' => 'SMK Anugerah Menjalin Kerja Sama dengan Industri Lokal'],
            ['title' => 'Perayaan Hari Guru Nasional Meriah dan Penuh Haru'],
        ];

        // Ambil semua nama file dari folder public/storage/dummy
        $imageFiles = glob(public_path('storage/dummy/*'));
        shuffle($imageFiles); // Biar acak aja gambarnya

        foreach ($data as $index => $item) {
            $imagePath = $imageFiles[$index] ?? null;

            News::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']) . '-' . Str::random(4),
                'content' => '<p>' . fake('id_ID')->paragraph(5) . '</p>',
                'image_path' => $imagePath ? 'dummy/' . basename($imagePath) : null,
                'user_id' => $author->id,
                'published_at' => now(),
            ]);
        }

        $this->command->info(count($data) . ' berita berhasil ditambahkan.');
    }
}