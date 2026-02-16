<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Review;

class Lesson extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('order');
        });
    }

}

