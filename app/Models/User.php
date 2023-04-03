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
        'ids',

        'name',
        'sex',
        'birth',
        'contact',
        'email',

        'address',
        'address_detail',
        'address_zipcode',

        'agree_marketing',

        'point',

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

    public function getFormatSocial()
    {
        if($this->social_platform === "kakaoCustom")
            return "카카오";

        if($this->social_platform === "kakao")
            return "카카오";

        if($this->social_platform === "naverCustom")
            return "네이버";

        if($this->social_platform === "naver")
            return "네이버";

        if($this->social_platform === "google")
            return "구글";

        if($this->social_platform === "facebook")
            return "페이스북";

        return "일반";
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function changePoint($type, $item = null)
    {
        $basic = Basic::first();

        if($type === PointHistoryType::REGISTER) {
            $point = $basic->point_register;

            $this->update(["point" => $this->point + $point]);

            $pointHistory = $this->pointHistories()->create([
                "type" => PointHistoryType::REGISTER,
                "point" => $point,
                "point_leave" => $this->point,
            ]);

            return [
                "success" => true,
                "point" => $point,
                "message" => "성공적으로 처리되었습니다."
            ];
        }

        if($type === PointHistoryType::RECOMMEND) {
            $point = $basic->point_recommend;

            $this->update(["point" => $this->point + $point]);

            $this->pointHistories()->create([
                "type" => PointHistoryType::RECOMMEND,
                "point" => $point,
                "point_leave" => $this->point,
            ]);

            return [
                "success" => true,
                "point" => $point,
                "message" => "성공적으로 처리되었습니다."
            ];
        }

        if($type === PointHistoryType::PURCHASE_ORDER) {
            $point = $item->product_point;

            $this->update(["point" => $this->point + $point]);

            $this->pointHistories()->create([
                "type" => PointHistoryType::PURCHASE_ORDER,
                "point" => $point,
                "point_leave" => $this->point,
            ]);

            return [
                "success" => true,
                "point" => $point,
                "message" => "성공적으로 처리되었습니다."
            ];
        }

        if($type === PointHistoryType::PURCHASE_CHECK) {
            $point = $item->point;

            if($this->point < $item->point)
                return [
                    "success" => false,
                    "point" => $point,
                    "message" => "마일리지가 부족합니다.",
                    "item" => $item
                ];

            $this->update(["point" => $this->point - $point]);

            $this->pointHistories()->create([
                "type" => PointHistoryType::PURCHASE_ORDER,
                "point" => -$point,
                "point_leave" => $this->point,
                "item" => $item
            ]);

            return [
                "success" => true,
                "point" => $point,
                "message" => "성공적으로 처리되었습니다.",
                "item" => $item
            ];
        }

        if($type === PointHistoryType::PURCHASE_PROTECT) {
            $point = $item->point;

            if($this->point < $item->point)
                return [
                    "success" => false,
                    "point" => $point,
                    "message" => "마일리지가 부족합니다.",
                    "item" => $item
                ];

            $this->update(["point" => $this->point - $point]);

            $this->pointHistories()->create([
                "type" => PointHistoryType::PURCHASE_CHECK,
                "point" => -$point,
                "point_leave" => $this->point,
            ]);

            return [
                "success" => true,
                "point" => $point,
                "message" => "성공적으로 처리되었습니다.",
                "item" => $item
            ];
        }

        return [
            "success" => false,
            "point" => 0,
            "message" => "충전 유형이 없습니다."
        ];
    }

    public function serviceChecks()
    {
        return $this->hasMany(ServiceCheck::class);
    }

    public function serviceProtects()
    {
        return $this->hasMany(ServiceProtect::class);
    }

    public function consultings()
    {
        return $this->hasMany(Consulting::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function finishes()
    {
        return $this->hasMany(Finish::class);
    }

    public function qnas()
    {
        return $this->hasMany(Qna::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function latestCars()
    {
        return $this->hasMany(LatestCar::class);
    }

    public function interestCars()
    {
        return $this->hasMany(InterestCar::class);
    }
}
