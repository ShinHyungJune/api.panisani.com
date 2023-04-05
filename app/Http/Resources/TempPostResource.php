<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TempPostResource extends JsonResource
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
            "board_id" => $this->board_id ?? "",
            "title" => $this->title ?? "",
            "description" => $this->description ?? "",
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i")
        ];
    }
}
