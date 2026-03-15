<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Review;
use App\Observers\ProfessionalObserver;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Professional::observe(ProfessionalObserver::class);

        Gate::policy(Appointment::class, ReviewPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
    }
}
