<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends ApiController
{
    public function index(Request $request)
    {
        $games = Game::latest()->paginate(8);

        return GameResource::collection($games);
    }
}
