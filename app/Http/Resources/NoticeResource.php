<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $next = Notice::find(Notice::where('id', '<', $this->id)->max('id'));

        $prev = Notice::find(Notice::where('id', '>', $this->id)->min('id'));

        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "important" => $this->important,
            "img" => $this->img ?? "",
            "year" => Carbon::make($this->created_at)->format("y"),
            "month" => Carbon::make($this->created_at)->format("m"),
            "date" => Carbon::make($this->created_at)->format("d"),
            "created_at" => Carbon::make($this->created_at)->format("Y-m-d H:i"),
            "prev" => $prev ?? "",
            "next" => $next ?? "",
            "files" => $this->files,
            "count_view" => $this->count_view,
        ];
    }
}
