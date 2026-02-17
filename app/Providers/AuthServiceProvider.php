<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /*
        |--------------------------------------------------------------------------
        | Role-Based Gates
        |--------------------------------------------------------------------------
        */

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('isInstructor', function ($user) {
            return $user->role === 'instructor';
        });

        Gate::define('isStudent', function ($user) {
            return $user->role === 'student';
        });

        /*
        |--------------------------------------------------------------------------
        | Global Admin Override
        |--------------------------------------------------------------------------
        */

        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
