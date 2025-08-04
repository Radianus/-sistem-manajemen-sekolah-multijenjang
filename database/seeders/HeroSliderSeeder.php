<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSlider;
use Database\Factories\HeroSliderFactory;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa slider dummy
        HeroSlider::factory()->count(5)->create();
        $this->command->info('Hero sliders seeded: ' . HeroSlider::count());
    }
}