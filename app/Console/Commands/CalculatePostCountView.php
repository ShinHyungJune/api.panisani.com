<?php

namespace App\Console\Commands;

use App\Models\Board;
use App\Models\Community;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculatePostCountView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:postCountView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '게시글 조회수 계산';

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
        $items = Post::orderBy("count_view", "desc")->cursor();

        foreach($items as $item){
            $yesterday = [
                "count_view" => $item->visits()
                    ->where("created_at", ">=", Carbon::now()->subDay()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subDay()->endOfDay())
                    ->count(),
                "count_comment" => $item->comments()
                    ->where("created_at", ">=", Carbon::now()->subDay()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subDay()->endOfDay())
                    ->count(),
                "count_like" => $item->likes()
                    ->where("created_at", ">=", Carbon::now()->subDay()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subDay()->endOfDay())
                    ->count(),
                "count_hate" => $item->hates()
                    ->where("created_at", ">=", Carbon::now()->subDay()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subDay()->endOfDay())
                    ->count()
            ];

            $lastWeek = [
                "count_view" => $item->visits()
                    ->where("created_at", ">=", Carbon::now()->subWeek()->startOfWeek()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subWeek()->endOfWeek()->endOfDay())
                    ->count(),
                "count_comment" => $item->comments()
                    ->where("created_at", ">=", Carbon::now()->subWeek()->startOfWeek()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subWeek()->endOfWeek()->endOfDay())
                    ->count(),
                "count_like" => $item->likes()
                    ->where("created_at", ">=", Carbon::now()->subWeek()->startOfWeek()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subWeek()->endOfWeek()->endOfDay())
                    ->count(),
                "count_hate" => $item->hates()
                    ->where("created_at", ">=", Carbon::now()->subWeek()->startOfWeek()->startOfDay())
                    ->where("created_at", "<=", Carbon::now()->subWeek()->endOfWeek()->endOfDay())
                    ->count(),
            ];

            $item->update([
                "count_view_yesterday" => $yesterday["count_view"],
                "count_comment_yesterday" => $yesterday["count_comment"],
                "count_like_yesterday" => $yesterday["count_like"],
                "count_hate_yesterday" => $yesterday["count_hate"],

                "count_view_last_week" => $lastWeek["count_view"],
                "count_comment_last_week" => $lastWeek["count_comment"],
                "count_like_last_week" => $lastWeek["count_like"],
                "count_hate_last_week" => $lastWeek["count_hate"],
            ]);
        }

        Post::query()->update(["hot" => 0]);

        // 게시글 자체가 핫 지정수보다 작다면 반절로 줄이기
        $countHot = Post::count() <= Post::$countHot ? floor(Post::$countHot / 2) : Post::$countHot;

        Post::orderBy("count_view_yesterday", "desc")->take($countHot)->update(["hot" => 1]);
    }
}
