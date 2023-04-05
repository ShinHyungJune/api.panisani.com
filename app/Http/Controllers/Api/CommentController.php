<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:500",
            "comment_id" => "nullable|integer",
            "post_id" => "nullable|integer"
        ]);

        $orderBy = $request->order_by ?? "created_at";

        $comments = $request->comment_id ? Comment::where("comment_id", $request->comment_id) : Comment::whereNull("comment_id");

        if($request->post_id)
            $comments = $comments->where("post_id", $request->post_id);

        if($request->word)
            $comments = $comments->whereHas("user", function($query) use($request){
                $query->where("nickname", "LIKE", "%{$request->word}%");
            });

        $comments = $comments->orderBy($orderBy, "desc")->paginate(10);

        return CommentResource::collection($comments);
    }

    public function indexByBest(Request $request)
    {
        $request->validate([
            "post_id" => "required|integer"
        ]);

        $comments = Comment::where("post_id", $request->post_id);

        $comments->update(["best" => 0]);

        $bestCount = floor($comments->count() * 20 / 100);

        $comments->orderBy("count_like", "desc")->take($bestCount)->update(["best" => 1]);

        $comments = Comment::where("post_id", $request->post_id)->where("best", 1)->paginate(10);

        return CommentResource::collection($comments);

    }

    public function store(Request $request)
    {
        $request->validate([
            "post_id" => "required|integer",
            "comment_id" => "nullable|integer"
        ]);

        $comment = auth()->user()->comments()->create($request->all());

        return $this->respondSuccessfully(CommentResource::make($comment));
    }

    public function destroy(Comment $comment)
    {
        if($comment->user_id != auth()->id())
            return $this->respondForbidden("자신의 댓글만 삭제할 수 있습니다.");

        $comment->delete();

        return $this->respondSuccessfully();
    }

}
