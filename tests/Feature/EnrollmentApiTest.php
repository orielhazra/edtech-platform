<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollmentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $token = auth('api')->login($user);

        return [$user, $token];
    }

    public function test_student_can_enroll()
    {
        $user = User::factory()->create(['role' => 'student']);
        $token = auth()->login($user);

        $course = Course::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/courses/{$course->id}/enroll");

        $response->assertStatus(201);
    }
}
