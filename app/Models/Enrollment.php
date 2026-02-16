<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;

class Enrollment extends Pivot
{
    use HasFactory, Notifiable;


    public $timestamps = false;

    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

