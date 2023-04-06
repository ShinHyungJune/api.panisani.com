<?php

namespace App\Models;

use App\Http\Resources\CommunityResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Community extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [
        "id", "user_id"
    ];

    public static $maxOpenBoardCount = 8;

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('img')->singleFile();
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getByChar($char)
    {
        $items = null;

        if($char == "ㄱ")
            $items = Community::where("accept", 1)->whereBetween("title", ["가", "깋"]);

        if($char == "ㄴ")
            $items = Community::where("accept", 1)->whereBetween("title", ["나", "닣"]);

        if($char == "ㄷ")
            $items = Community::where("accept", 1)->whereBetween("title", ["다", "딯"]);

        if($char == "ㄹ")
            $items = Community::where("accept", 1)->whereBetween("title", ["라", "맇"]);

        if($char == "ㅁ")
            $items = Community::where("accept", 1)->whereBetween("title", ["마", "밓"]);

        if($char == "ㅂ")
            $items = Community::where("accept", 1)->whereBetween("title", ["바", "빟"]);

        if($char == "ㅅ")
            $items = Community::where("accept", 1)->whereBetween("title", ["사", "싷"]);

        if($char == "ㅇ")
            $items = Community::where("accept", 1)->whereBetween("title", ["아", "잏"]);

        if($char == "ㅈ")
            $items = Community::where("accept", 1)->whereBetween("title", ["자", "짛"]);

        if($char == "ㅊ")
            $items = Community::where("accept", 1)->whereBetween("title", ["차", "칳"]);

        if($char == "ㅋ")
            $items = Community::where("accept", 1)->whereBetween("title", ["카", "킿"]);

        if($char == "ㅌ")
            $items = Community::where("accept", 1)->whereBetween("title", ["타", "팋"]);

        if($char == "ㅍ")
            $items = Community::where("accept", 1)->whereBetween("title", ["파", "핗"]);

        if($char == "ㅎ")
            $items = Community::where("accept", 1)->whereBetween("title", ["하", "힣"]);

        if(!$items)
            $items = Community::where("accept", 1)->where("title", "LIKE", "{$char}%");

        return CommunityResource::collection($items->orderBy("count_view", "desc")->paginate(30));
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function alreadyMaxOpenBoard()
    {
        return $this->boards()->count() >= self::$maxOpenBoardCount;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
