<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $other;
    protected $board;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = User::factory()->create();
        $this->other = User::factory()->create();
        $this->board = Board::factory()->create();

        $this->actingAs($this->user);

        $this->form = [
            "user_id" => $this->user->id,
            "board_id" => $this->board->id,
            "title" => "테스트",
            "description" => "내용입니다",
        ];
    }

    /** @test */
    public function 어제기준_조회수순으로_게시글목록을_조회할_수_있다()
    {
        $this->artisan('calculate:searchRanking');

        for($i = 0; $i < 10; $i++){
            Post::factory()->create([
                "count_view_yesterday" => rand(1,10000)
            ]);
        }

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_view_yesterday"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["count_view_yesterday"] >= $item["count_view_yesterday"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 저번주기준_조회수순으로_게시글_목록을_조회할_수_있다()
    {
        for($i = 0; $i < 10; $i++){
            $post = Post::factory()->create();

            Visit::factory()->count(rand(0,100))->create([
                "post_id" => $post->id,
                "created_at"=> Carbon::now()->subWeek()
            ]);
        }

        $this->artisan('calculate:postCountView');

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_view_last_week"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["count_view_last_week"] >= $item["count_view_last_week"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 등록순으로_게시글목록을_조회할_수_있다()
    {
        for($i = 0; $i < 10; $i++){
            Post::factory()->create([
                "created_at" => Carbon::now()->subDays(rand(0,1000))
            ]);
        }

        $items = $this->json("get", "/api/posts", [
            "order_by" => "created_at"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["created_at"] >= $item["created_at"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 조회수순으로_게시글목록을_조회할_수_있다()
    {
        for($i = 0; $i < 10; $i++){
            Post::factory()->create([
                "count_view" => rand(1,10000)
            ]);
        }

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_view"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["count_view"] >= $item["count_view"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 좋아요순으로_게시글목록을_조회할_수_있다()
    {
        for($i = 0; $i < 10; $i++){
            Post::factory()->create([
                "count_like" => rand(1,10000)
            ]);
        }

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_like"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["count_like"] >= $item["count_like"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 댓글순으로_게시글목록을_조회할_수_있다()
    {
        for($i = 0; $i < 10; $i++){
            Post::factory()->create([
                "count_comment" => rand(1,10000)
            ]);
        }

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_comment"
        ])->decodeResponseJson()["data"];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem["count_comment"] >= $item["count_comment"]);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 한시간내_조회수순으로_게시글_목록을_조회할_수_있다()
    {
        $rankings = [
            "3" => Post::factory()->create(),
            "1" => Post::factory()->create(),
            "2" => Post::factory()->create()
        ];

        Visit::factory()->count(30)->create([
            "post_id" => $rankings[1]->id,
            "created_at" => Carbon::now()->subMinutes(30)
        ]);

        Visit::factory()->count(20)->create([
            "post_id" => $rankings[2]->id,
            "created_at" => Carbon::now()->subMinutes(30)
        ]);

        Visit::factory()->count(30)->create([
            "post_id" => $rankings[2]->id,
            "created_at" => Carbon::now()->subHours(2)
        ]);

        Visit::factory()->count(10)->create([
            "post_id" => $rankings[3]->id,
            "created_at" => Carbon::now()
        ]);

        Visit::factory()->count(50)->create([
            "post_id" => $rankings[3]->id,
            "created_at" => Carbon::now()->subHours(2)
        ]);

        $items = $this->json("get", "/api/posts", [
            "order_by" => "count_view_last_hour"
        ])->decodeResponseJson()["data"];

        $this->assertEquals($items[0]["id"], $rankings[1]->id);
        $this->assertEquals($items[1]["id"], $rankings[2]->id);
        $this->assertEquals($items[2]["id"], $rankings[3]->id);
    }

    /** @test */
    public function 특정_커뮤니티의_게시글목록을_조회할_수_있다()
    {
        $community = Community::factory()->create();

        $otherCommunity = Community::factory()->create();

        $communityPosts = Post::factory()->count(10)->create([
            "community_id" => $community->id
        ]);

        $otherCommunityPosts = Post::factory()->count(10)->create([
            "community_id" => $otherCommunity->id
        ]);

        $items = $this->json("get", "/api/posts", [
            "community_id" => $community->id
        ])->decodeResponseJson()["data"];

        $this->assertCount(count($communityPosts), $items);
    }

    /** @test */
    public function 특정_게시판에_포함된_게시글목록을_조회할_수_있()
    {
        $board = Board::factory()->create();

        $otherBoard = Board::factory()->create();

        $boardPosts = Post::factory()->count(10)->create([
            "board_id" => $board->id
        ]);

        $otherBoardPosts = Post::factory()->count(10)->create([
            "board_id" => $otherBoard->id
        ]);

        $items = $this->json("get", "/api/posts", [
            "board_id" => $board->id
        ])->decodeResponseJson()["data"];

        $this->assertCount(count($boardPosts), $items);
    }

    /** @test */
    public function 검색어가_포함된_게시글목록을_조회할_수_있다()
    {
        $word = "테스트";

        $wordIncludePosts = Post::factory()->count(10)->create([
            "title" => "123".$word."ss"
        ]);

        $wordExcludePosts = Post::factory()->count(10)->create([
            "title" => "00"
        ]);

        $items = $this->json("get", "/api/posts", [
            "word" => $word
        ])->decodeResponseJson()["data"];

        $this->assertCount(count($wordIncludePosts), $items);
    }

    /** @test */
    public function 게시글상세를_조회할_수_있다()
    {
        $post = Post::factory()->create();

        $item = $this->json("get", "/api/posts/".$post->id)->decodeResponseJson()["data"];

        $this->assertEquals($item["id"], $post->id);
    }

    /** @test */
    public function 게시글상세를_조회하면_게시글_조회수가_올라간다()
    {
        $post = Post::factory()->create();

        $item = $this->json("get", "/api/posts/".$post->id)->decodeResponseJson()["data"];

        $post = Post::find($post->id);

        $this->assertEquals(1, $post->count_view);
    }

    /** @test */
    public function 사용자는_게시글을_생성할_수_있다()
    {
        $this->post("/api/posts/", $this->form);

        $this->assertCount(1, Post::get());
    }

    /** @test */
    public function 조회수_집계를_실행하면_이전일_기준_추천수_상위핫게시글수만큼_핫이_배당된다()
    {
        $commonPosts = Post::factory()->count(10)->create();

        $hotPosts = Post::factory()->count(Post::$countHot)->create();

        foreach($commonPosts as $commonPost){
            Visit::factory()->count(10)->create([
                "post_id" => $commonPost->id,
                "created_at" => Carbon::now()
            ]);
        }

        foreach($hotPosts as $hotPost){
            Visit::factory()->count(10)->create([
                "post_id" => $hotPost->id,
                "created_at" => Carbon::yesterday()
            ]);
        }

        $this->artisan('calculate:postCountView');

        $items = Post::where("hot", 1)->get();

        $this->assertCount(count($hotPosts), $items);
    }
}
