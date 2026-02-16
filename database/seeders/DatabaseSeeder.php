<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Create Admin
        |--------------------------------------------------------------------------
        */

        User::factory()->admin()->create([
            'name' => 'System Admin',
            'password' => bcrypt('admin123')
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Instructors
        |--------------------------------------------------------------------------
        */

        $instructors = User::factory()
            ->count(5)
            ->instructor()
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Create Students
        |--------------------------------------------------------------------------
        */

        $students = User::factory()
            ->count(20)
            ->student()
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Create Courses + Lessons
        |--------------------------------------------------------------------------
        */

        $courses = Course::factory()
            ->count(10)
            ->make()
            ->each(function ($course) use ($instructors) {

                $course->instructor_id =
                    $instructors->random()->id;

                $course->save();

                for ($i = 1; $i <= 5; $i++) {

                    Lesson::factory()->create([
                        'course_id' => $course->id,
                        'order' => $i,
                        'title' => "Lesson {$i}: " . fake()->sentence(3),
                    ]);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Create Enrollments
        |--------------------------------------------------------------------------
        */

        foreach ($students as $student) {
            $randomCourses = $courses->random(3);

            foreach ($randomCourses as $course) {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now()
                ]);

                Review::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'rating' => rand(3,5),
                    'comment' => fake()->sentence()
                ]);
            }
        }
    }
}
