<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\YoutubeResource;
use App\Models\Youtube;
use Illuminate\Http\Request;

class YoutubeController extends ApiController
{
    public function index(Request $request)
    {
        $items = Youtube::orderBy("order", "asc")->paginate(30);

        return YoutubeResource::collection($items);
    }


}
