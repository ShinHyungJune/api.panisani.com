<?php

namespace App\Console\Commands;

use App\Models\Board;
use App\Models\Community;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateBoardCountView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:boardCountView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '어제자 게시판 조회수 계산';

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
        $items = Board::orderBy("count_view_yesterday", "desc")->cursor();

        foreach($items as $item){
            $countViewYesterday = $item->visits()
                ->where("created_at", ">=", Carbon::now()->subDay()->startOfDay())
                ->where("created_at", "<=", Carbon::now()->subDay()->endOfDay())
                ->count();

            $item->update([
                "count_view" => $item->count_view + $countViewYesterday,
                "count_view_yesterday" => $countViewYesterday
            ]);
        }
    }
}
