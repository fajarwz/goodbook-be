<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    public function scopeUpdatedBetween($query, $from, $until)
    {
        return $query->whereBetween('updated_at', [Carbon::parse($from), Carbon::parse($until)->endOfMonth()]);
    }

    public function scopeRatings($query, $value)
    {
        return $query->whereBetween('rating', [$value, 5]);
    }
}
