<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Enrollment;

class EnsureEnrolled
{
    public function handle(Request $request, Closure $next)
    {
        $courseId = $request->route('course');

        $exists = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();

        if (!$exists) {
            return response()->json([
                'message' => 'You must enroll in this course'
            ], 403);
        }

        return $next($request);
    }
}
