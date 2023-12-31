<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cover_type_id',
        'title',
        'short_description',
        'description',
        'number_of_pages',
        'avg_rating',
        'rater_count',
        'cover',
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coverType()
    {
        return $this->belongsTo(CoverType::class);
    }
}
