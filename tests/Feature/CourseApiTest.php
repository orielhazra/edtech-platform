<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate($role = User::ROLE_INSTRUCTOR)
    {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $token = auth('api')->login($user);

        return [$user, $token];
    }

    /*
    |--------------------------------------------------------------------------
    | Public Course List Test
    |--------------------------------------------------------------------------
    */
    public function test_public_can_view_courses()
    {
        Course::factory()->count(3)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    /*
    |--------------------------------------------------------------------------
    | Instructor Can Create Course
    |--------------------------------------------------------------------------
    */
    public function test_instructor_can_create_course()
    {
        [$user, $token] = $this->authenticate(User::ROLE_INSTRUCTOR);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/courses', [
                'title' => 'Laravel Advanced',
                'description' => 'Master Laravel',
                'price' => 100,
                'level' => 'advanced'
            ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Laravel Advanced']);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Cannot Create Course
    |--------------------------------------------------------------------------
    */
    public function test_student_cannot_create_course()
    {
        [$user, $token] = $this->authenticate(User::ROLE_STUDENT);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/courses', [
                'title' => 'Unauthorized Course',
                'description' => 'Should fail',
                'price' => 50
            ]);

        $response->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | Owner Can Update Course
    |--------------------------------------------------------------------------
    */
    public function test_owner_can_update_course()
    {
        [$user, $token] = $this->authenticate();

        $course = Course::factory()->create([
            'instructor_id' => $user->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/courses/{$course->id}", [
                'title' => 'Updated Title'
            ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);
    }

    /*
    |--------------------------------------------------------------------------
    | Non-owner Cannot Delete Course
    |--------------------------------------------------------------------------
    */
    public function test_non_owner_cannot_delete_course()
    {
        [$owner, $ownerToken] = $this->authenticate();
        [$otherUser, $otherToken] = $this->authenticate();

        $course = Course::factory()->create([
            'instructor_id' => $owner->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer $otherToken")
            ->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(403);
    }
}
