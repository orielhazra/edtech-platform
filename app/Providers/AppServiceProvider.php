<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Review;

use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\ReviewPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Lesson::class => LessonPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
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
    }
}
