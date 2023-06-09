<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Qna extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ["id", "user_id"];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('files');
    }

    public function getFilesAttribute()
    {
        $result = [];

        if($this->hasMedia('files')) {
            $medias = $this->getMedia('files');

            foreach($medias as $media){
                $result[] = [
                    "name" => $media->file_name,
                    "url" => $media->getFullUrl()
                ];
            }
        }

        return $result;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
