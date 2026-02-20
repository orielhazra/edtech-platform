<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Review;

class Course extends Model
{
    use HasFactory;


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'title',
        'description',
        'price',
        'level',
        'instructor_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'price' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Instructor (User)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Lessons (ordered)
    public function lessons()
    {
        return $this->hasMany(Lesson::class)
                    ->orderBy('order');
    }

    // Enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Students through enrollments
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',
            'course_id',
            'user_id'
        )->withTimestamps();
    }

    // Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    // Enrollment count
    public function getEnrollmentCountAttribute()
    {
        return $this->enrollments()->count();
    }

    // Average rating
    public function getAverageRatingAttribute()
    {
        return round(
            $this->reviews()->avg('rating') ?? 0,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', "%{$keyword}%")
                     ->orWhere('description', 'like', "%{$keyword}%");
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

}
