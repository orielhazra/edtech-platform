<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'level' => $this->level,

            /*
            |--------------------------------------------------------------------------
            | Instructor Info
            |--------------------------------------------------------------------------
            */
            'instructor' => [
                'id' => $this->instructor?->id,
                'name' => $this->instructor?->name,
                'email' => $this->instructor?->email,
            ],

            /*
            |--------------------------------------------------------------------------
            | Lessons (Loaded Only If Eager Loaded)
            |--------------------------------------------------------------------------
            */
            'lessons' => LessonResource::collection(
                $this->whenLoaded('lessons')
            ),

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */
            'total_lessons' => $this->whenCounted('lessons'),
            'total_enrollments' => $this->whenCounted('enrollments'),
            'average_rating' => number_format($this->reviews()->avg('rating') ?? 0, 1),


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
