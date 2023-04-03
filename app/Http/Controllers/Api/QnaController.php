<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QnaResource;
use App\Models\Qna;
use Illuminate\Http\Request;

class QnaController extends ApiController
{
    public function index(Request $request)
    {
        $qnas = auth()->user()->qnas()->latest()->paginate(10);

        return QnaResource::collection($qnas);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:500",
            "description" => "required|string|max:500000"
        ]);

        $qna = auth()->user()->qnas()->create([
            "title" => $request->title,
            "description" => $request->description,
        ]);

        if(is_array($request->file('files'))){
            foreach($request->file("files") as $file){
                $qna->addMedia($file)->toMediaCollection("files", "s3");
            }
        }

        return $this->respondSuccessfully(QnaResource::make($qna));
    }
}
