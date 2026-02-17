<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Course;
use \App\Models\Lesson;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     *  protected $model = Lesson::class;
    */

    public function definition(): array
    {
        return [

            'title' => fake()->sentence(3),
            'content' => fake()->paragraphs(3, true),
            'course_id' => \App\Models\Course::factory()
        ];

    }
}
