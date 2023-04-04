<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger("community_id");
            $table->foreign("community_id")->references("id")->on("communities")->onDelete("cascade");
            $table->string("title"); // 제목
            $table->integer("order")->default(0); // 순서
            $table->boolean("open")->default(1); // 공개여부
            $table->unsignedBigInteger("count_view")->default(0); // 조회수
            $table->unsignedBigInteger("count_view_yesterday")->default(0); // 어제자 조회수
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
        Schema::dropIfExists('boards');
    }
}
