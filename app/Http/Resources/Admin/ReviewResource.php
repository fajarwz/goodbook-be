<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'review' => $this->review,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('F j, Y'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('F j, Y'),
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
            'book' => [
                'id' => $this->book_id,
                'title' => $this->book->title,
            ],
        ];
    }
}
