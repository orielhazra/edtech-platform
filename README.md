EdTech Platform – LMS API

A RESTful Learning Management System (LMS) API built with Laravel 11 and
JWT authentication. Supports course management, lessons, enrollments,
reviews, and role-based access control.

  --------------------
  SETUP INSTRUCTIONS
  --------------------

1.  Clone Repository git clone
    https://github.com/yourusername/edtech-platform.git cd
    edtech-platform

2.  Install Dependencies composer install

3.  Create Environment File cp .env.example .env

    Update .env with: APP_NAME=EdTechPlatform APP_ENV=local
    APP_DEBUG=true APP_URL=http://localhost

    DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306
    DB_DATABASE=edtech_db DB_USERNAME=root DB_PASSWORD=

4.  Generate Application Key php artisan key:generate

5.  Run Migrations php artisan migrate

6.  Start Development Server php artisan serve

API base URL: http://localhost:8000/api

  -----------------
  JWT SETUP STEPS
  -----------------

1.  Install JWT Package composer require tymon/jwt-auth

2.  Publish Config php artisan vendor:publish –provider=“Tymon”

3.  Generate JWT Secret php artisan jwt:secret

4.  Update config/auth.php

    ‘defaults’ => [ ‘guard’ => ‘api’, ‘passwords’ => ‘users’,],

    ‘guards’ => [ ‘api’ => [ ‘driver’ => ‘jwt’, ‘provider’ => ‘users’,
    ],],

5.  Protect Routes Route::middleware(‘auth:api’)->group(function () {
    Route::get(‘/auth/me’, [AuthController::class, ‘me’]); });

6.  Include Token in Requests Authorization: Bearer {your_token}

  -------------------
  API FLOW OVERVIEW
  -------------------

Authentication Flow: Register → Login → Receive JWT Token → Access
Protected Routes

Course Flow: Instructor creates course → Adds lessons → Students enroll
→ Students review

Enrollment Flow: Student logs in → Enrolls in course → Access lessons

  ------------------------
  USER ROLES EXPLANATION
  ------------------------

ADMIN - Full system access - Manage all courses - Manage users Role
value: admin

INSTRUCTOR - Create and manage own courses - Add/edit lessons - View
enrollments Role value: instructor

STUDENT - View courses - Enroll in courses - Access lessons - Leave
reviews Role value: student
