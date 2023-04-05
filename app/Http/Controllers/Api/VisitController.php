<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            "post_id" => "required|integer",
        ]);

        $post = Post::find($request->post_id);

        if(!$post)
            return $this->respondForbidden("존재하지 않는 게시글입니다.");

        Visit::create([
            "user_id" => auth()->user() ? auth()->user()->id() : null,
            "post_id" => $post->id,
        ]);
    }
}
