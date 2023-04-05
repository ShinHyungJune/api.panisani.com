<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Basic;
use App\Models\Board;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\CarFuel;
use App\Models\CarCreator;
use App\Models\CarTransmission;
use App\Models\CarType;
use App\Models\Community;
use App\Models\Consulting;
use App\Models\Faq;
use App\Models\Notice;
use App\Models\PayMethod;
use App\Models\PointHistory;
use App\Models\Post;
use App\Models\Qna;
use App\Models\Review;
use App\Models\Sale;
use App\Models\ServiceCheck;
use App\Models\ServiceProtect;
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
        DB::statement("SET foreign_key_checks=1");

        $this->createUsers();
        $this->createYoutubes();
        $this->createCommunities();
        $this->createBoards();
        $this->createPosts();
        // $this->createBanners();
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
            $createdItem = Community::factory()->create($item);

            $createdItem->addMedia(public_path($this->thumbnails[rand(0, count($this->thumbnails) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createBoards()
    {
        $communities = Community::get();

        foreach($communities as $community){
            Board::factory()->count(rand(1,20))->create([
                "community_id" => $community->id,
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
            ]);
        }
    }

}
