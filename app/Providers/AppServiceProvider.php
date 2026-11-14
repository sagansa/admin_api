<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

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
        Vite::prefetch(concurrency: 3);

        // Token Sanctum dibaca/ditulis di DB auth terpusat (mysql_auth),
        // bukan DB default — lihat App\Models\PersonalAccessToken.
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
