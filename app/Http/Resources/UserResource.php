<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "social_platform" => $this->getFormatSocial(),
            "img" => $this->img ?? "",

            "nickname" => $this->nickname ?? "",
            "description" => $this->description ?? "",
            "birth" => $this->birth ?? "",
            "email" => $this->email ?? "",

            "count_post" => $this->posts()->count(),
            "count_subscription" => $this->receiveSubscriptions()->count(),
            "is_subscription" => $this->isSubscription,

            "created_at" => Carbon::make($this->created_at)->format("Y-m-d"),
            "updated_at" => Carbon::make($this->updated_at)->format("Y-m-d H:i"),
            "deleted_at" => $this->deleted_at ? Carbon::make($this->deleted_at)->format("Y-m-d H:i") : "",
        ];
    }
}
