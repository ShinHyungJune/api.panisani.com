<?php

namespace App\Console\Commands;

use App\Models\Board;
use App\Models\Community;
use App\Models\Post;
use App\Models\SearchHistory;
use App\Models\SearchRanking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateSearchRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:searchRanking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '검색순위 계산';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = DB::table('search_histories')
            ->select('title', DB::raw('COUNT(*) as total'))
            ->where("created_at", ">=", Carbon::now()->subMinutes(10))
            ->groupBy("title")
            ->having("total", ">", 0)
            ->orderBy("total", "desc")
            ->limit(100)
            ->get();

        $rank = 1;

        foreach($items as $item){
            $prevRanking = SearchRanking::where("rank_current", $rank)->first();

            if(!$prevRanking)
                SearchRanking::create(["title" => $item->title, "rank_current" => $rank]);

            if($prevRanking){
                $prevRanking->title == $item->title
                    ? $prevRanking->update(["rank_current" => $rank, "rank_prev" => $prevRanking->rank_current])
                    : $prevRanking->update(["title" => $item->title, "rank_current" => $rank]);
            }

            $rank++;
        }
    }
}
