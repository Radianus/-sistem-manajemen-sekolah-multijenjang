<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use Database\Factories\SettingFactory;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan hanya ada satu record settings (ID 1)
        Setting::firstOrCreate(['id' => 1], Setting::factory()->make()->toArray());
        $this->command->info('Default system settings seeded.');
    }
}
