<?php

namespace App\Http\Resources\Member\Book;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexBookResource extends JsonResource
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
            'short_description' => $this->short_description,
            'avg_rating' => $this->avg_rating,
            'cover' => $this->cover,
            'published_at' => Carbon::createFromFormat('Y-m-d', $this->published_at)->format('F j, Y'),
            'author' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
        ];
    }
}
