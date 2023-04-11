<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuggestionResource;
use Illuminate\Http\Request;

class SuggestionController extends ApiController
{
    public function index(Request $request)
    {
        $suggestions = auth()->user()->suggestions()->latest()->paginate(15);

        return SuggestionResource::collection($suggestions);
    }

    public function store(Request $request)
    {
        $request->validate([
            "description" => "required|string|max:500000"
        ]);

        $suggestion = auth()->user()->suggestions()->create([
            "description" => $request->description,
        ]);

        if(is_array($request->file('files'))){
            foreach($request->file("files") as $file){
                $suggestion->addMedia($file)->toMediaCollection("files", "s3");
            }
        }

        return $this->respondSuccessfully(SuggestionResource::make($suggestion));
    }
}
