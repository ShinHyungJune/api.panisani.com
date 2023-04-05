<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class HateController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            "comment_id" => "required|integer"
        ]);

        $comment = Comment::find($request->comment_id);

        if(!$comment)
            return $this->respondForbidden("존재하지 않는 댓글입니다.");

        $hate = auth()->user()->hates()->where("comment_id", $comment->id)->first();

        $hate ? $hate->delete() : auth()->user()->hates()->create($request->all());

        return $this->respondSuccessfully();
    }
}
