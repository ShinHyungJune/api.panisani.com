<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:500",
            "user_id" => "nullable|integer",
            "order_by" => "nullable|string|max:500",
        ]);

        $items = Community::where("accept", 1);

        if($request->word)
            $items = $items->where("title", "LIKE", "%".$request->word."%");


    }
}
