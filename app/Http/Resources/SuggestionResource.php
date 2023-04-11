<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
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
            "user" => $this->user ? UserResource::make($this->user) : "",
            "description" => $this->description,
            "answer" => $this->answer ?? "",
            "files" => $this->files,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
            "updated_at" => Carbon::make($this->updated_at)->format("Y-m-d H:i")
        ];
    }
}
