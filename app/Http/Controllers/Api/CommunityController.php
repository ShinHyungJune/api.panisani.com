<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use App\Models\SearchHistory;
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

        $request["take"] = $request->take ?? 20;

        $items = Community::where("accept", 1);

        if($request->order_by)
            $items = $items->orderBy($request->order_by, "desc");

        if($request->word) {
            $items = $items->where("title", "LIKE", "%" . $request->word . "%");

            SearchHistory::create([
                "user_id" => auth()->user() ? auth()->id() : null,
                "title" => $request->word
            ]);
        }

        if($request->user_id)
            $items = $items->where("user_id", $request->user_id);

        $items = $items->paginate($request->take);

        return CommunityResource::collection($items);
    }

    public function indexByChar(Request $request)
    {
        $items = [
            "ㄱ" => Community::getByChar("ㄱ"),
            "ㄴ" => Community::getByChar("ㄴ"),
            "ㄷ" => Community::getByChar("ㄷ"),
            "ㄹ" => Community::getByChar("ㄹ"),
            "ㅁ" => Community::getByChar("ㅁ"),
            "ㅂ" => Community::getByChar("ㅂ"),
            "ㅅ" => Community::getByChar("ㅅ"),
            "ㅇ" => Community::getByChar("ㅇ"),
            "ㅈ" => Community::getByChar("ㅈ"),
            "ㅊ" => Community::getByChar("ㅊ"),
            "ㅋ" => Community::getByChar("ㅋ"),
            "ㅌ" => Community::getByChar("ㅌ"),
            "ㅍ" => Community::getByChar("ㅍ"),
            "ㅎ" => Community::getByChar("ㅎ"),

            "a" => Community::getByChar("a"),
            "b" => Community::getByChar("b"),
            "c" => Community::getByChar("c"),
            "d" => Community::getByChar("d"),
            "e" => Community::getByChar("e"),
            "f" => Community::getByChar("f"),
            "g" => Community::getByChar("g"),
            "h" => Community::getByChar("h"),
            "i" => Community::getByChar("i"),
            "j" => Community::getByChar("j"),
            "k" => Community::getByChar("k"),
            "l" => Community::getByChar("l"),
            "m" => Community::getByChar("m"),
            "n" => Community::getByChar("n"),
            "o" => Community::getByChar("o"),
            "p" => Community::getByChar("p"),
            "q" => Community::getByChar("q"),
            "r" => Community::getByChar("r"),
            "s" => Community::getByChar("s"),
            "t" => Community::getByChar("t"),
            "u" => Community::getByChar("u"),
            "v" => Community::getByChar("v"),
            "w" => Community::getByChar("w"),
            "x" => Community::getByChar("x"),
            "y" => Community::getByChar("y"),
            "z" => Community::getByChar("z"),
        ];

        return $items;
    }

    public function show(Community $community)
    {
        if(!$community->accept)
            return $this->respondForbidden("아직 게시허용되지 않는 커뮤니티입니다.");

        return $this->respondSuccessfully(CommunityResource::make($community));
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|unique:communities|max:500",
            "description" => "required|string|max:500000",
            "url" => "required|string|max:500",
            "has_admin" => "nullable|boolean",
            "img" => "required|file"
        ]);

        $item = auth()->user()->communities()->create($request->except("count_view"));

        if($request->img)
            $item->addMedia($request->img)->toMediaCollection("img", "s3");

        return $this->respondSuccessfully(CommunityResource::make($item));
    }
}
