<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchRankingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $diff = $this->rank_prev ? $this->rank_prev - $this->rank_current : $this->rank_current;

        return [
            "id" => $this->id,
            'title' => $this->title,
            "rank_current" => $this->rank_current,
            "rank_prev" => $this->rank_prev,
            "diff" => $diff,
            "new" => $this->rank_prev == null ? 1 : 0,
        ];
    }
}
