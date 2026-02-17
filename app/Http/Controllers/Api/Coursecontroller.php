<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/courses
    |--------------------------------------------------------------------------
    | Public listing with search, filter & pagination
    */
    public function index(Request $request)
    {
        $query = Course::with('instructor');

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by level
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        // Filter by max price
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $courses = $query->paginate(10);

        return CourseResource::collection($courses);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/courses
    |--------------------------------------------------------------------------
    | Instructor only
    */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'level'       => 'required|in:beginner,intermediate,advanced',
        ]);

        $validated['instructor_id'] = Auth::id();

        $course = Course::create($validated);

        return new CourseResource($course->load('instructor'));
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/courses/{id}
    |--------------------------------------------------------------------------
    */
    public function show(Course $course)
    {
        $course->load(['instructor', 'lessons', 'reviews']);

        return new CourseResource($course);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/courses/{id}
    |--------------------------------------------------------------------------
    | Owner instructor only
    */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'sometimes|string',
            'price'       => 'sometimes|numeric|min:0',
            'level'       => 'sometimes|in:beginner,intermediate,advanced',
        ]);

        $course->update($validated);

        return new CourseResource($course->fresh()->load('instructor'));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE /api/courses/{id}
    |--------------------------------------------------------------------------
    | Owner instructor only
    */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Course deleted successfully'
        ]);
    }
}

