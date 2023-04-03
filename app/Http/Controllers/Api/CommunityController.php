<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunityController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:500",
            "user_id" => "nullable|integer",
        ]);
    }
}
