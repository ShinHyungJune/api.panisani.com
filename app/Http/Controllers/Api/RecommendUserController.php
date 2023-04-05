<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendUserResource;
use App\Models\RecommendUser;
use Illuminate\Http\Request;

class RecommendUserController extends ApiController
{
    public function index(Request $request)
    {
        $items = RecommendUser::latest()->paginate(15);

        return RecommendUserResource::collection($items);
    }
}
