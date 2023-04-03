<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QnaResource extends JsonResource
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
            "user_ids" => $this->user ? $this->user->ids : "탈퇴회원",

            "admin" => $this->admin ? AdminResource::make($this->admin) : "",
            "admin_name" => $this->admin_name,

            "title" => $this->title,
            "description" => $this->description,
            "answer" => $this->answer,
            "files" => $this->files,
            "files_answer" => $this->files_answer,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
            "updated_at" => Carbon::make($this->updated_at)->format("Y-m-d H:i")
        ];
    }
}
