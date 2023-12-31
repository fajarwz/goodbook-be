<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'number_of_pages' => $this->number_of_pages,
            'avg_rating' => $this->avg_rating,
            'rater_count' => $this->rater_count,
            'cover' => $this->cover,
            'published_at' => Carbon::createFromFormat('Y-m-d', $this->published_at)->format('F j, Y'),
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('F j, Y'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('F j, Y'),
            'author' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
            'cover_type' => [
                'id' => $this->cover_type_id,
                'name' => $this->coverType->name,
            ],
        ];
    }
}
