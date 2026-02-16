<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'content'   => $this->content,
            'order'     => $this->order,
            'course_id' => $this->course_id,
            'created_at'=> $this->created_at,
        ];
    }
}

