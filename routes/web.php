<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('courses.index');
})->name('index');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Course Related Page (Blade)
|--------------------------------------------------------------------------
*/

Route::get('/courses/{id}', function ($id) {
    return view('courses.show', compact('id'));
});

Route::get('/create', function () {
    return view('courses.create');
});

Route::get('/courses/{id}/edit', function ($id) {
    return view('courses.edit', compact('id'));
});

Route::get('/courses/{id}/review', function ($id) {
    return view('courses.review', compact('id'));
});