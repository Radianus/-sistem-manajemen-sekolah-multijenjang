<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\User;
use Database\Factories\SchoolClassFactory;
use Carbon\Carbon;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa kelas spesifik (jika perlu)
        SchoolClass::firstOrCreate(
            ['name' => 'Kelas 10 IPA 1'],
            ['level' => 'SMA', 'grade_level' => '10', 'academic_year' => '2025/2026'] // <-- PASTIKAN INI ADALAH 'level'
        );
        SchoolClass::firstOrCreate(
            ['name' => 'Kelas 7 SMP A'],
            ['level' => 'SMP', 'grade_level' => '7', 'academic_year' => '2025/2026'] // <-- PASTIKAN INI ADALAH 'level'
        );
        SchoolClass::firstOrCreate(
            ['name' => 'Kelas 6 SD C'],
            ['level' => 'SD', 'grade_level' => '6', 'academic_year' => '2025/2026'] // <-- PASTIKAN INI ADALAH 'level'
        );

        // Buat lebih banyak kelas menggunakan factory
        SchoolClass::factory()->count(15)->create();

        // Tidak perlu lagi update whereNull('jenjang')

        $this->command->info('School classes seeded.');
    }
}