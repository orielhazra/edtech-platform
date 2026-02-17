<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return "API working";
});

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

});

/*
|--------------------------------------------------------------------------
| Courses
|--------------------------------------------------------------------------
*/

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::get('/courses/{course}/reviews', [ReviewController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (JWT Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::post('/auth/refresh', function () {
    return response()->json([
        'token' => auth()->refresh()
    ]);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Instructor Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:instructor,admin')->group(function () {

        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update'])
        ->middleware('owns.course');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->middleware('owns.course');

        Route::post('/courses/{course}/lessons', [LessonController::class, 'store']);
        Route::put('/lessons/{lesson}', [LessonController::class, 'update'])
        ->middleware('owns.course');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])
        ->middleware('owns.course');

        Route::get('/instructor/courses', [CourseController::class, 'myCourses']);
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:student')->group(function () {

        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll']);
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);

        Route::post('/courses/{course}/review', [ReviewController::class, 'store'])
        ->middleware('enrolled');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/users', [AuthController::class, 'allUsers']);
        Route::delete('/admin/users/{user}', [AuthController::class, 'deleteUser']);

    });

});
