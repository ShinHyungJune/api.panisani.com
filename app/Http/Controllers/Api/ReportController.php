<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            "comment_id" => "nullable|integer",
            "post_id" => "nullable|integer",
            "user_id" => "nullable|integer",
            "reason" => "required|string|max:5000"
        ]);

        $item = null;

        if($request->comment_id)
            $item = Comment::find($request->comment_id);

        if($request->post_id)
            $item = Post::find($request->post_id);

        if($request->user_id)
            $item = User::find($request->user_id);

        if(!$item)
            return $this->respondForbidden("존재하지 않는 대상입니다.");

        $report = $item->reports()->updateOrCreate([
            "user_id" => auth()->id()
        ],[
            "user_id" => auth()->id(),
            "reason" => $request->reason
        ]);

        return $this->respondSuccessfully(ReportResource::make($report));
    }
}
