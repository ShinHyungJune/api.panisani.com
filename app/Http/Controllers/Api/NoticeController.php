<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoticeResource;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{
    public function index(Request $request)
    {
        $notices = Notice::latest()->paginate(20);

        return NoticeResource::collection($notices);
    }
}
