<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ["id", "user_id"];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('img')->singleFile();
        $this->addMediaCollection('file')->singleFile();
    }

    public function getImgAttribute()
    {
        if($this->hasMedia('img')) {
            $media = $this->getMedia('img')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getFileAttribute()
    {
        if($this->hasMedia('file')) {
            $media = $this->getMedia('file')[0];

            return [
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::created(function($model){
            $model->post->update([
                "count_comment" => $model->post->count_comment + 1
            ]);
        });

        self::deleted(function($model){
            $model->post->update([
                "count_comment" => $model->post->count_comment - 1,
                "count_like" => $model->post->count_like - $model->count_like,
                "count_hate" => $model->post->count_hate - $model->count_hate,
            ]);
        });

        self::updating(function($model){
            $diff = [
                "count_like" => $model->count_like - $model->getOriginal("count_like"),
                "count_hate" => $model->count_hate - $model->getOriginal("count_hate"),
            ];

            $model->post->update([
                "count_like" => $model->post->count_like + $diff["count_like"],
                "count_hate" => $model->post->count_hate + $diff["count_hate"],
            ]);
        });
    }

    public function getIsLikeAttribute()
    {
        if(!auth()->user())
            return 0;

        return auth()->user()->likes()->where("comment_id", $this->id)->first() ? 1 : 0;
    }

    public function getIsHateAttribute()
    {
        if(!auth()->user())
            return 0;

        return auth()->user()->hates()->where("comment_id", $this->id)->first() ? 1 : 0;
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
