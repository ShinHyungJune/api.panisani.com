<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Notice extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "title",
        "description",
        "important",
        "count_view"
    ];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('files');
    }

    public function getFilesAttribute()
    {
        $items = [];

        if($this->hasMedia('files')) {
            $medias = $this->getMedia('files');

            foreach($medias as $media){
                $items[] = [
                    "name" => $media->file_name,
                    "url" => $media->getFullUrl()
                ];
            }
        }

        return $items;
    }
}
