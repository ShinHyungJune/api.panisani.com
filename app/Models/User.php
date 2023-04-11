<?php

namespace App\Models;

use App\Enums\PointHistoryType;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    use SoftDeletes;

    protected $fillable = [
        'email',
        'nickname',
        'birth',
        "description",

        'password',
        "verified_at",

        "social_id",
        "social_platform",

        "reason_leave_out",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["img"];

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

    public function getIsSubscriptionAttribute()
    {
        if(!auth()->user())
            return 0;

        if(auth()->user()->giveSubscriptions()->where("target_user_id", $this->id)->first())
            return 1;

        return 0;
    }

    public function getFormatSocial()
    {
        if($this->social_platform === "kakaoCustom")
            return "kakao";

        if($this->social_platform === "kakao")
            return "kakao";

        if($this->social_platform === "naverCustom")
            return "naver";

        if($this->social_platform === "naver")
            return "naver";

        if($this->social_platform === "google")
            return "google";

        if($this->social_platform === "facebook")
            return "facebook";

        return "";
    }

    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function tempPosts()
    {
        return $this->hasMany(TempPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hates()
    {
        return $this->hasMany(Hate::class);
    }

    public function giveSubscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function receiveSubscriptions()
    {
        return $this->hasMany(Subscription::class, "target_user_id");
    }

    public function reports() // 신고 받은 이력(신고한 이력 아님)
    {
        return $this->hasMany(Report::class, "target_user_id");
    }

    public function scraps()
    {
        return $this->hasMany(Scrap::class);
    }
}
