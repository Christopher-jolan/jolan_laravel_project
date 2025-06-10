<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GymSession;
use App\Observers\GymSessionObserver;

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
        GymSession::observe(GymSessionObserver::class);
    }
}
