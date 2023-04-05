<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpecialResource;
use App\Models\Special;
use Illuminate\Http\Request;

class SpecialController extends ApiController
{
    public function index()
    {
        $items = Special::latest()->paginate(20);

        return SpecialResource::collection($items);
    }
}
