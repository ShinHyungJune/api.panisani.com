<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class YoutubeResource extends JsonResource
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
            "url" => $this->url,
            "thumbnail" => $this->thumbnail,
            "key" => $this->key,
            "title" => $this->title,
            "order" => $this->order,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
        ];
    }
}
