<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchRankingResource;
use App\Models\SearchRanking;
use Illuminate\Http\Request;

class SearchRankingController extends ApiController
{
    public function index(Request $request)
    {
        $searchRankings = SearchRanking::orderBy("rank_current", "asc")->take(20)->paginate(20);

        return SearchRankingResource::collection($searchRankings);
    }
}
