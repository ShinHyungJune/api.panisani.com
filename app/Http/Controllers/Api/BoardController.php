<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Community;
use App\Models\Faq;
use Illuminate\Http\Request;

class BoardController extends ApiController
{
    public function indexByMe(Request $request)
    {
        $boards = auth()->user()->boards()->orderBy("order", "asc")->paginate(100);

        return BoardResource::collection($boards);
    }

    public function index(Request $request)
    {
        $request->validate([
            "community_id" => "nullable|integer",
            "order_by" => "nullable|string|max:500",
        ]);

        $orderBy = $request->order_by ?? "order";

        $align = $orderBy == "order" ? "asc" : "desc";

        $boards = $request->mine ?  new Board() : Board::where("open", 1);

        $boards = $boards->orderBy($orderBy, $align);
        if($request->community_id)
            $boards = $boards->where("community_id", $request->community_id);

        $boards = $boards->paginate(20);

        return BoardResource::collection($boards);
    }

    public function store(Request $request)
    {
        $request->validate([
            "community_id" => "required|integer",
            "title" => "required|string|max:500",
        ]);

        $community = Community::find($request->community_id);

        if(!$community)
            return $this->respondForbidden("존재하지 않는 커뮤니티입니다.");

        if($community->user_id != auth()->id())
            return $this->respondForbidden("커뮤니티의 주인이 아닙니다.");

        if($community->alreadyMaxOpenBoard())
            return $this->respondForbidden("이미 게시판 최대공개수를 초과했습니다.");

        $board = auth()->user()->boards()->create($request->except(["count_view"]));

        return $this->respondSuccessfully(BoardResource::make($board));
    }

    public function destroy(Board $board)
    {
        if($board->user_id != auth()->id())
            return $this->respondForbidden("커뮤니티의 주인이 아닙니다.");

        $board->delete();

        return $this->respondSuccessfully();
    }

    public function update(Board $board, Request $request)
    {
        $request->validate([
            "open" => "nullable|boolean"
        ]);

        if($board->user_id != auth()->id())
            return $this->respondForbidden("커뮤니티의 주인이 아닙니다.");

        $openBoardsCount = $board->community->boards()->where("open", 1)->count();

        // 비공개 게시판을 공개로 전환시도
        if(!$board->open && $request->open && $openBoardsCount >= Community::$maxOpenBoardCount)
            return $this->respondForbidden("이미 게시판 최대공개수를 초과했습니다.");

        $board->update($request->except(["community_id", "count_view"]));

        return $this->respondSuccessfully(BoardResource::make($board));
    }

    public function up(Board $board)
    {
        $prevOrder = $board->order;
        $targetItem = $board->community->boards()->orderBy("order", "desc")->where("id", "!=", $board->id)->where("order", "<=", $board->order)->first();

        if($targetItem) {
            $changeOrder = $targetItem->order == $board->order ? $board->order - 1 : $targetItem->order;
            $board->update(["order" => $changeOrder]);
            $targetItem->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    public function down(Board $board)
    {
        $prevOrder = $board->order;
        $targetItem = $board->community->boards()->orderBy("order", "asc")->where("id", "!=", $board->id)->where("order", ">=", $board->order)->first();

        if($targetItem) {
            $changeOrder = $targetItem->order == $board->order ? $board->order + 1 : $targetItem->order;
            $board->update(["order" => $changeOrder]);
            $targetItem->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();

    }
}
