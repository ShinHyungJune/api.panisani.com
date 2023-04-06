<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Http\Resources\PostResource;
use App\Models\Board;
use App\Models\Community;
use App\Models\Post;
use App\Models\SearchHistory;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            "community_id" => "nullable|integer",
            "board_id" => "nullable|integer",
            "user_id" => "nullable|integer",
            "order_by" => "nullable|string|max:500",
            "word" => "nullable|string|max:500"
        ]);

        $request["take"] = $request->take ?? 20;

        $orderBy = $request->order_by ?? "created_at";

        // 공개된 게시판 게시글만 노출
        $items = Post::whereHas("board", function($query){
            $query->where("open", 1);
        });

        if($orderBy == "count_view_last_hour")
            $items = $items->selectRaw("posts.*, visits.post_id, visits.created_at")->join('visits', 'posts.id', '=', 'visits.post_id')
                ->where('visits.created_at', '>=', Carbon::now()->subHour()->setMinute(0)->setSecond(0))
                ->groupBy('posts.id')
                ->orderByRaw('COUNT(*) DESC');
        elseif($orderBy == "count_view_last_week")
            $items = $items->selectRaw("posts.*, visits.post_id, visits.created_at")->join('visits', 'posts.id', '=', 'visits.post_id')
                ->where('visits.created_at', '>=', Carbon::now()->startOfWeek()->startOfDay())
                ->groupBy('posts.id')
                ->orderByRaw('COUNT(*) DESC');
        elseif($orderBy == "count_view_last_month")
            $items = $items->selectRaw("posts.*, visits.post_id, visits.created_at")->join('visits', 'posts.id', '=', 'visits.post_id')
                ->where('visits.created_at', '>=', Carbon::now()->startOfMonth()->startOfDay())
                ->groupBy('posts.id')
                ->orderByRaw('COUNT(*) DESC');
        else
            $items = $items->orderBy($orderBy, "desc");

        if($request->community_id)
            $items = $items->where("community_id", $request->community_id);

        if($request->board_id)
            $items = $items->where("board_id", $request->board_id);

        if($request->user_id)
            $items = $items->where("user_id", $request->user_id);

        if($request->word) {
            $items = $items->where("title", "LIKE", "%{$request->word}%");

            SearchHistory::create([
                "user_id" => auth()->user() ? auth()->id() : null,
                "title" => $request->word
            ]);
        }

        $items = $items->paginate($request->take);

        return PostResource::collection($items);
    }

    public function show(Post $post, Request $request)
    {
        Visit::create([
            "user_id" => auth()->user() ? auth()->id() : null,
            "ip" => $request->ip(),
            "post_id" => $post->id
        ]);

        return $this->respondSuccessfully(PostResource::make($post));
    }

    public function store(Request $request)
    {
        $request->validate([
            "board_id" => "required|integer",
            "title" => "required|string|max:500",
            "description" => "required|string|max:50000",
        ]);

        $board = Board::find($request->board_id);

        if(!$board)
            return $this->respondForbidden("존재하지 않는 게시판입니다.");

        $post = auth()->user()->posts()->create([
            "community_id" => $board->community_id,
            "board_id" => $board->id,
            "title" => $request->title,
            "description" => $request->description
        ]);

        return $this->respondSuccessfully(PostResource::make($post));
    }

    public function destroy(Post $post)
    {
        if($post->user_id != auth()->id())
            return $this->respondForbidden("자신의 게시글만 삭제할 수 있습니다.");

        $post->delete();

        return $this->respondSuccessfully();
    }

    public function update(Post $post, Request $request)
    {
        $request->validate([
            "title" => "required|string|max:500",
            "description" => "required|string|max:50000",
        ]);

        if($post->user_id != auth()->id())
            return $this->respondForbidden("자신의 게시글만 수정할 수 있습니다.");

        $post->update([
            "title" => $request->title,
            "description" => $request->description
        ]);

        return $this->respondSuccessfully(PostResource::make($post));
    }
}
