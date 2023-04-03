<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "admin" => $this->admin ? 1 : 0,
            "social_platform" => $this->getFormatSocial(),
            "ids" => $this->ids ?? "",
            "name" => $this->name ?? "",
            "sex" => $this->sex ?? "",
            "birth" => $this->birth ,
            "contact" => $this->contact ?? "",
            "email" => $this->email ?? "",
            "ids_recommend" => $this->ids_recommend ?? "",
            "address" => $this->address ?? "",
            "address_detail" => $this->address_detail ?? "",
            "address_zipcode" => $this->address_zipcode ?? "",
            "point" => $this->point ?? "",

            "created_at" => Carbon::make($this->created_at)->format("Y-m-d"),
            "updated_at" => Carbon::make($this->updated_at)->format("Y-m-d H:i"),
            "deleted_at" => $this->deleted_at ? Carbon::make($this->deleted_at)->format("Y-m-d H:i") : "",
        ];
    }
}
