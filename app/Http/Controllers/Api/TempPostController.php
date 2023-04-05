<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TempPostResource;
use App\Models\TempPost;
use Illuminate\Http\Request;

class TempPostController extends ApiController
{
    public function index(Request $request)
    {
        $items = auth()->user()->tempPosts()->latest()->paginate(10);

        return TempPostResource::collection($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "nullable|string|max:500",
            "description" => "nullable|string|max:50000",
            "board_id" => "nullable|integer"
        ]);

        $item = auth()->user()->tempPosts()->create($request->all());

        return $this->respondSuccessfully(TempPostResource::make($item));
    }

    public function destroy(TempPost $tempPost)
    {
        if($tempPost->user_id != auth()->id())
            return $this->respondForbidden("자신의 임시저장글만 삭제할 수 있습니다.");

        $tempPost->delete();

        return $this->respondSuccessfully();
    }
}
