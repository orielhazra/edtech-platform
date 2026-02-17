<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Course;

class OwnsCourse
{
    public function handle(Request $request, Closure $next)
    {
        $course = $request->route('course');

        if (!($course instanceof \App\Models\Course)) {
            // fallback if route binding didn't happen for some reason
            $course = \App\Models\Course::find($course);
        }

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        if ($course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden: not your course'], 403);
        }

        return $next($request);
    }

}
