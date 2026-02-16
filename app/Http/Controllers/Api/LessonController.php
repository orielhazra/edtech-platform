<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LessonResource;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/courses/{course}/lessons
    |--------------------------------------------------------------------------
    | Public - List lessons of a course
    */
    public function index(Course $course)
    {
        $lessons = $course->lessons()
                          ->orderBy('order')
                          ->paginate(10);

        return LessonResource::collection($lessons);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/courses/{course}/lessons
    |--------------------------------------------------------------------------
    | Instructor only (owner of course)
    */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'order'   => 'required|integer|min:1',
        ]);

        $validated['course_id'] = $course->id;

        $lesson = Lesson::create($validated);

        return new LessonResource($lesson);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/lessons/{lesson}
    |--------------------------------------------------------------------------
    | Instructor only (owner of course)
    */
    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        $validated = $request->validate([
            'title'   => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'order'   => 'sometimes|integer|min:1',
        ]);

        $lesson->update($validated);

        return new LessonResource($lesson->fresh());
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE /api/lessons/{lesson}
    |--------------------------------------------------------------------------
    | Instructor only (owner of course)
    */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('delete', $lesson);

        $lesson->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lesson deleted successfully'
        ]);
    }
}

