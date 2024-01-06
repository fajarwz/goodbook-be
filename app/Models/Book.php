<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopePublishedBetween($query, $from, $until)
    {
        return $query->whereBetween('published_at', [Carbon::parse($from), Carbon::parse($until)->endOfMonth()]);
    }

    public function scopeRatings($query, $value)
    {
        return $query->whereBetween('avg_rating', [$value, 5]);
    }
}
