<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;
use \App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Course::class;
    
    public function definition(): array
    {
        return [

            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(0, 200),
            'level' => fake()->randomElement(['beginner','intermediate','advanced']),
            'instructor_id' => User::factory()->instructor(),
        ];

    }
}
