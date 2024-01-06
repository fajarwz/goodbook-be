<?php

namespace App\Http\Resources\Member\Book;

use App\Http\Resources\Member\GenreResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowBookResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'avg_rating' => $this->avg_rating,
            'number_of_pages' => $this->number_of_pages,
            'cover' => $this->cover,
            'published_at' => Carbon::createFromFormat('Y-m-d', $this->published_at)->format('F j, Y'),
            'author' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'image' => $this->user->image,
                'book_count' => \App\Models\Book::where('user_id', $this->user_id)->count(),
            ],
            'genres' => GenreResource::collection($this->genres),
            'cover_type' => [
                'id' => $this->cover_type_id,
                'name' => $this->coverType->name,
            ],
        ];
    }
}
