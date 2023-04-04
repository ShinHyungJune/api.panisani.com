<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");

            $table->unsignedBigInteger("community_id");
            $table->foreign("community_id")->references("id")->on("communities")->onDelete("cascade");

            $table->unsignedBigInteger("board_id");
            $table->foreign("board_id")->references("id")->on("boards")->onDelete("cascade");

            $table->string("title"); // 제목
            $table->text("description")->nullable(); // 내용
            $table->boolean("hot")->default(0); // 핫한 게시글 여부

            $table->unsignedBigInteger("count_view")->default(0);
            $table->unsignedBigInteger("count_comment")->default(0);
            $table->unsignedBigInteger("count_recommend")->default(0);
            $table->unsignedBigInteger("count_like")->default(0);
            $table->unsignedBigInteger("count_hate")->default(0);

            $table->unsignedBigInteger("count_view_yesterday")->default(0); // 어제자 조회수
            $table->unsignedBigInteger("count_comment_yesterday")->default(0); // 어제자 댓글수
            $table->unsignedBigInteger("count_recommend_yesterday")->default(0); // 어제자 추천수
            $table->unsignedBigInteger("count_like_yesterday")->default(0); // 어제자 좋아요수
            $table->unsignedBigInteger("count_hate_yesterday")->default(0); // 어제자 싫어요수

            $table->unsignedBigInteger("count_view_last_week")->default(0); // 저번주 조회수
            $table->unsignedBigInteger("count_comment_last_week")->default(0); // 저번주 댓글수
            $table->unsignedBigInteger("count_recommend_last_week")->default(0); // 저번주 추천수
            $table->unsignedBigInteger("count_like_last_week")->default(0); // 저번주 좋아요수
            $table->unsignedBigInteger("count_hate_last_week")->default(0); // 저번주 싫어요수
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
