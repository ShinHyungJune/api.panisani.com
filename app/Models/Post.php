<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public static $countHot = 20;

    protected $guarded = [
        "id", "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function recommends()
    {
        return $this->hasMany(Recommend::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hates()
    {
        return $this->hasMany(Hate::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
