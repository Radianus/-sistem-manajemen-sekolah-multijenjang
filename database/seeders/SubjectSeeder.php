<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjectsData = [
            // Umum
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI', 'level' => 'Umum'],
            ['name' => 'Pendidikan Pancasila', 'code' => 'PPKN', 'level' => 'Umum'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'level' => 'Umum'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG', 'level' => 'Umum'],
            ['name' => 'Matematika', 'code' => 'MTK', 'level' => 'Umum'],
            ['name' => 'PJOK', 'code' => 'PJOK', 'level' => 'Umum'],
            ['name' => 'Seni Budaya', 'code' => 'SENI', 'level' => 'Umum'],

            // SD
            ['name' => 'Ilmu Pengetahuan Alam & Sosial', 'code' => 'IPAS', 'level' => 'SD'],

            // SMP
            ['name' => 'Ilmu Pengetahuan Alam', 'code' => 'IPA', 'level' => 'SMP'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'code' => 'IPS', 'level' => 'SMP'],
            ['name' => 'Informatika', 'code' => 'INF', 'level' => 'SMP'],

            // SMA
            ['name' => 'Fisika', 'code' => 'FIS', 'level' => 'SMA'],
            ['name' => 'Kimia', 'code' => 'KIM', 'level' => 'SMA'],
            ['name' => 'Biologi', 'code' => 'BIO', 'level' => 'SMA'],
            ['name' => 'Ekonomi', 'code' => 'EKO', 'level' => 'SMA'],
            ['name' => 'Sejarah', 'code' => 'SEJ', 'level' => 'SMA'],

            // SMK (contoh)
            ['name' => 'Dasar Desain Grafis', 'code' => 'DDG', 'level' => 'SMK'],
            ['name' => 'Pemrograman Web', 'code' => 'PWEB', 'level' => 'SMK'],
        ];

        foreach ($subjectsData as $data) {
            Subject::firstOrCreate(['name' => $data['name']], $data);
        }
        $this->command->info('Subjects seeded.');
    }
}
