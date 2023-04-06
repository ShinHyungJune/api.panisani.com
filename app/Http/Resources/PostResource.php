<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            "id" => $this->id,
            "user" => $this->user ? [
                "id" => $this->user->id,
                "nickname" => $this->user->nickname,
            ] : "",
            "thumbnail" => $this->thumbnail ?? "/images/no-image.png",
            "community_id" => $this->community_id,

            "board" => [
                "id" => $this->board_id,
                "title" => $this->board->title,
            ],

            "title" => $this->title,
            "description" => $this->description,

            "count_view" => $this->count_view,
            "count_comment" => $this->count_comment,
            "count_like" => $this->count_like,
            "count_hate" => $this->count_hate,

            "count_view_yesterday" => $this->count_view_yesterday,
            "count_view_last_week" => $this->count_view_last_week,

            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
            "format_created_at" => Carbon::make($this->created_at)->format("Y.m.d"),
        ];
    }
}
