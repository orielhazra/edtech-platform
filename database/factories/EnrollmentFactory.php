<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;
use \App\Models\Course;
use \App\Models\Enrollment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     
    *   protected $model = Enrollment::class;
    */

    public function definition(): array
    {
        return [

            'user_id' => \App\Models\User::factory()->student(),
            'course_id' => \App\Models\Course::factory(),
            'enrolled_at' => now(),
        ];
    }
}
