<?php

namespace App\Providers;

use App\Models\Meeting; // Import Model
use App\Policies\MeetingPolicy; // Import Policy
use Illuminate\Support\Facades\Gate; // Import Gate
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
        Gate::policy(Meeting::class, MeetingPolicy::class);
    }
}
