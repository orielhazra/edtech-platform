<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/courses/{course}/reviews
    |--------------------------------------------------------------------------
    | Public
    */
    public function index(Course $course)
    {
        $reviews = $course->reviews()
                          ->with('user')
                          ->latest()
                          ->paginate(10);

        return ReviewResource::collection($reviews);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/courses/{course}/reviews
    |--------------------------------------------------------------------------
    | Only enrolled students
    */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        // Role check
        if ($user->role !== 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Only students can submit reviews'
            ], 403);
        }

        // Enrollment check
        $isEnrolled = $user->enrolledCourses()
                           ->where('course_id', $course->id)
                           ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'status' => false,
                'message' => 'You must enroll in the course before reviewing'
            ], 403);
        }

        // Prevent duplicate review
        if (Review::where('user_id', $user->id)
                  ->where('course_id', $course->id)
                  ->exists()) {

            return response()->json([
                'status' => false,
                'message' => 'You have already reviewed this course'
            ], 409);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review = Review::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'rating'    => $validated['rating'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return new ReviewResource($review->load('user'));
    }
}

