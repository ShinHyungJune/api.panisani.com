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
                "img" => $this->user->img ?? ""
            ],
            "post" => [
                "id" => $this->post->id
            ],
            "comments" => CommentResource::collection($this->comments()->paginate(50)),
            "description" => $this->description,
            "count_comment" => $this->comments()->count(),
            "count_like" => (int) $this->count_like,
            "count_hate" => (int) $this->count_hate,
            "best" => $this->best,
            "isLike" => $this->isLike,
            "isHate" => $this->isHate,
            "img" => $this->img ?? "",
            "file" => $this->file ?? "",
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
            "format_created_at" => Carbon::make($this->created_at)->format("Y.m.d"),
        ];
    }
}
