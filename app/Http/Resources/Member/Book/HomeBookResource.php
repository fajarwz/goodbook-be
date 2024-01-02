<?php

namespace App\Http\Resources\Member\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeBookResource extends JsonResource
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
            'avg_rating' => $this->avg_rating,
            'cover' => $this->cover,
            'author' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
        ];
    }
}
