<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Faq;
use App\Models\Game;
use App\Models\Notice;
use App\Models\PayMethod;
use App\Models\Post;
use App\Models\Qna;
use App\Models\RecommendUser;
use App\Models\SearchRanking;
use App\Models\Special;
use App\Models\TempPost;
use App\Models\User;
use App\Models\Youtube;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSeeder extends Seeder
{
    protected $thumbnails = [
        "imgs/game1.jpg",
        "imgs/game2.jpg",
        "imgs/game3.jpg",
        "imgs/game4.jpg",
        "imgs/game5.jpg",
        "imgs/game6.jpg",
        "imgs/game7.jpg",
    ];

    protected $avatars = [
        "imgs/avatar1.png",
        "imgs/avatar2.png",
    ];

    protected $user;
    protected $users = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        User::truncate();
        Youtube::truncate();
        Community::truncate();
        Board::truncate();
        Post::truncate();
        TempPost::truncate();
        Comment::truncate();
        Special::truncate();
        SearchRanking::truncate();
        RecommendUser::truncate();
        Game::truncate();
        Notice::truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->createUsers();
        $this->createYoutubes();
        $this->createCommunities();
        $this->createBoards();
        $this->createPosts();
        $this->createComments();
        $this->createSpecials();
        $this->createSearchRankings();
        $this->createRecommendUsers();
        $this->createGames();
        $this->createNotices();
        // $this->createBanners();
    }

    public function createNotices()
    {
        $items = Notice::factory()->count(25)->create();
    }

    public function createGames()
    {
        $items = Game::factory()->count(8)->create();

        foreach($items as $item){
            $item->addMedia(public_path($this->thumbnails[rand(0, count($this->thumbnails) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createRecommendUsers()
    {
        foreach($this->users as $user){
            RecommendUser::factory()->create([
                "user_id" => $user->id
            ]);
        }
    }

    public function createUsers()
    {
        $this->user = User::factory()->create([
            "email" => "test@naver.com",
            "password" => Hash::make("test@naver.com")
        ]);

        $this->users[] = $this->user;

        $this->users[] = User::factory()->create([
            "email" => "ssa4141@naver.com",
            "password" => Hash::make("ssa4141@naver.com")
        ]);

        $this->users[0]->addMedia(public_path($this->avatars[0]))->preservingOriginal()->toMediaCollection("img", "s3");
        $this->users[1]->addMedia(public_path($this->avatars[1]))->preservingOriginal()->toMediaCollection("img", "s3");

    }

    public function createYoutubes()
    {
        $items = [
            "https://www.youtube.com/watch?v=3l3t05bsbW8",
            "https://www.youtube.com/watch?v=ceAXKowQyRk",
            "https://www.youtube.com/watch?v=LY5zjSu2mIY",
            "https://www.youtube.com/watch?v=UdzlCS-34Ts",
            "https://www.youtube.com/watch?v=85c_UVhhjrI",
            "https://www.youtube.com/watch?v=cFsGCYlbt-s",
            "https://www.youtube.com/watch?v=Th62twZftf4",
            "https://www.youtube.com/watch?v=2sosdA22MuA",
            "https://www.youtube.com/watch?v=x_HVs3bLEt0",
            "https://www.youtube.com/watch?v=UdzlCS-34Ts",
            "https://www.youtube.com/watch?v=85c_UVhhjrI",
            "https://www.youtube.com/watch?v=cFsGCYlbt-s",
            "https://www.youtube.com/watch?v=Th62twZftf4",
            "https://www.youtube.com/watch?v=2sosdA22MuA",
            "https://www.youtube.com/watch?v=x_HVs3bLEt0",
        ];

        foreach($items as $item){
            Youtube::factory()->create([
                "url" => $item
            ]);
        }
    }

    public function createCommunities()
    {
        $items = [
            [
                "title" => "가나다",
            ],
            [
                "title" => "강강",
            ],
            [
                "title" => "다이스",
            ],
            [
                "title" => "라게임",
            ],
            [
                "title" => "가게임",
            ],
            [
                "title" => "하게임",
            ],
            [
                "title" => "힣게임",
            ],
            [
                "title" => "쿅게임",
            ],
            [
                "title" => "apple",
            ],
            [
                "title" => "fgame",
            ],
            [
                "title" => "bame",
            ],
            [
                "title" => "dame",
            ],
            [
                "title" => "eame",
            ],
        ];

        foreach($items as $item){
            $createdItem = Community::factory([
                "created_at" => Carbon::now()->subDays(rand(0,30)),
            ])->create($item);

            $createdItem->addMedia(public_path($this->thumbnails[rand(0, count($this->thumbnails) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createBoards()
    {
        $communities = Community::get();

        foreach($communities as $community){
            Board::factory()->count(rand(1,8))->create([
                "community_id" => $community->id,
                "created_at" => Carbon::now()->subDays(rand(0,30)),
            ]);
        }
    }

    public function createPosts()
    {
        $boards = Board::get();

        foreach($boards as $board){
            Post::factory()->count(rand(1,20))->create([
                "user_id" => $this->users[rand(0,1)]->id,
                "community_id" => $board->community_id,
                "board_id" => $board->id,
                "created_at" => Carbon::now()->subDays(rand(0,30)),
            ]);
        }
    }

    public function createComments()
    {
        $posts = Post::get();

        foreach($posts as $post){
            Comment::factory()->count(rand(5,20))->create([
                "user_id" => $this->users[rand(0,1)]->id,
                "post_id" => $post->id,
            ]);
        }
    }

    public function createSpecials()
    {
        Special::factory()->count(12)->create();
    }

    public function createSearchRankings()
    {
        SearchRanking::factory()->count(100)->create();
    }
}
