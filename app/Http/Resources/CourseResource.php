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

            /*
            |--------------------------------------------------------------------------
            | Instructor Info
            |--------------------------------------------------------------------------
            */
            'instructor' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
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
            'average_rating' => round($this->reviews_avg_rating ?? 0, 2),

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
