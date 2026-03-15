<?php

namespace App\Providers;

use App\Models\Professional;
use App\Observers\ProfessionalObserver;
use Illuminate\Support\ServiceProvider;

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
        Professional::observe(ProfessionalObserver::class);
    }
}
