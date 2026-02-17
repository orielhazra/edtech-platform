<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CourseResource;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/courses/{course}/enroll
    |--------------------------------------------------------------------------
    | Student only
    */
    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Role check (in case no middleware)
        if ($user->role !== 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Only students can enroll in courses'
            ], 403);
        }

        // Prevent duplicate enrollment
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Already enrolled in this course'
            ], 409);
        }

        // Attach enrollment
        $user->enrolledCourses()->attach($course->id, [
            'enrolled_at' => now(),
            'status'      => 'active',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Successfully enrolled',
            'course'  => new CourseResource($course->load('instructor'))
        ], 201);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $enrollment = \App\Models\Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $request->course_id,
        ]);

        return response()->json([
            'message' => 'Enrolled successfully',
            'data' => $enrollment
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | GET /api/my-courses
    |--------------------------------------------------------------------------
    | Student enrolled courses
    */
    public function myCourses()
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Only students can view enrolled courses'
            ], 403);
        }

        $courses = $user->enrolledCourses()
                        ->with('instructor')
                        ->paginate(10);

        return CourseResource::collection($courses);
    }
}
