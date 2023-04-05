<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            "user" => [
                "id" => $this->user->id,
                "nickname" => $this->user->nickname,
            ],
            "description" => $this->description,
            "count_comment" => $this->comments()->count(),
            "count_like" => $this->count_like,
            "count_hate" => $this->count_hate,
            "best" => $this->best,
            "isLike" => $this->isLike,
            "isHate" => $this->isHate,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i")
        ];
    }
}
