<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Course;

class OwnsCourse
{
    public function handle(Request $request, Closure $next)
    {
        $courseId = $request->route('course');

        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }

        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        if ($course->instructor_id !== auth()->id()) {
            return response()->json([
                'message' => 'Forbidden: not your course'
            ], 403);
        }

        return $next($request);
    }
}
