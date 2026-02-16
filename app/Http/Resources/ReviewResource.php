<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'rating'     => $this->rating,
            'comment'    => $this->comment,
            'student'    => $this->user->name ?? null,
            'created_at' => $this->created_at,
        ];
    }
}

