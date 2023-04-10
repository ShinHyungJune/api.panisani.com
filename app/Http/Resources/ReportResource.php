<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            "user" => $this->user,
            "post" => $this->post,
            "comment" => $this->comment,
            "targetUser" => $this->targetUser,
            "reason" => $this->reason,
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i")
        ];
    }
}
