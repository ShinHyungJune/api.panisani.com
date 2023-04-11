<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScrapResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class ScrapController extends ApiController
{
    public function index(Request $request)
    {
        $scraps = auth()->user()->scraps()->orderBy("updated_at", "desc")->paginate(20);

        return ScrapResource::collection($scraps);
    }

    public function store(Request $request)
    {
        $request->validate([
            "post_id" => "required|integer"
        ]);

        $post = Post::find($request->post_id);

        if(!$post)
            return $this->respondForbidden("존재하지 않는 게시글입니다.");

        $scrap = auth()->user()->scraps()->where("post_id", $post->id)->updateOrCreate([
            "post_id" => $post->id
        ], [
            "post_id" => $post->id
        ]);

        return $this->respondSuccessfully();
    }
}
