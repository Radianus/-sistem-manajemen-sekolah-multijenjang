<?php

namespace App\Providers;

use App\Console\Commands\CheckUngradedSubmissions;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Schedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            $settings = Setting::first(); // Ambil record pengaturan pertama (ID 1)
            $view->with('globalSettings', $settings); // Kirim ke semua view dengan nama globalSettings
        });
    }
}
