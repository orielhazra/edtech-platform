<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
