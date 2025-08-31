<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FetchUnsplashImages extends Command
{
    protected $signature = 'dummy:unsplash {jumlah=5} {query=education} {prefix?}';
    protected $description = 'Download dummy images from Unsplash and save to storage';

    public function handle()
    {
        $jumlah = (int) $this->argument('jumlah');
        $query = $this->argument('query');
        $prefix = $this->argument('prefix') ?? Str::slug($query);
        // $this->info('🧹 Cleaning up old files in dummy folder...');
        // Storage::disk('public')->deleteDirectory('dummy');
        // Storage::disk('public')->makeDirectory('dummy');
        $this->info("📦 Fetching $jumlah images with query '$query' and prefix '$prefix'...");

        for ($i = 1; $i <= $jumlah; $i++) {
            $response = Http::withHeaders([
                'Accept-Version' => 'v1',
                'Authorization' => 'Client-ID ' . config('services.unsplash.access_key'),
            ])->get('https://api.unsplash.com/photos/random', [
                'query' => $query,
                'orientation' => 'landscape',
            ]);

            if ($response->successful()) {
                $imageUrl = $response->json('urls.regular');
                $imageContent = file_get_contents($imageUrl);
                $filename = 'dummy/' . $prefix . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.jpg';
                Storage::disk('public')->put($filename, $imageContent);
                $this->info("[$i/$jumlah] ✅ Saved: $filename");
            } else {
                $this->error("[$i/$jumlah] ❌ Failed to fetch image.");
            }

            sleep(1); // biar gak di-rate-limit
        }

        $this->info('🎉 Done! All images fetched and saved in storage/app/public/dummy/');
    }
}
