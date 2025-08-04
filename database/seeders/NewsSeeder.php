<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Database\Factories\NewsFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

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


        $allFiles = Storage::disk('public')->files('dummy');
        $imageFiles = array_filter($allFiles, function ($file) {
            return str_starts_with(basename($file), 'news-');
        });

        if (empty($imageFiles)) {
            $this->command->warn('Tidak ada gambar dengan prefix "news-" di folder dummy.');
            return;
        }
        foreach ($imageFiles as $file) {
            $title = fake('id_ID')->sentence(6);
            News::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(4),
                'content' => '<p>' . fake('id_ID')->paragraph(5) . '</p>',
                'image_path' => $file, // path relatif terhadap storage/public
                'user_id' => $author->id,
                'published_at' => now(),
            ]);
        }

        $this->command->info(count($imageFiles) . ' berita berhasil dibuat dengan gambar dari dummy/news-');
    }
}