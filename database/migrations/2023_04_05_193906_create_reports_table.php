<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");

            $table->unsignedBigInteger("post_id")->nullable();
            $table->foreign("post_id")->references("id")->on("posts")->onDelete("cascade");

            $table->unsignedBigInteger("comment_id")->nullable();
            $table->foreign("comment_id")->references("id")->on("comments")->onDelete("cascade");

            $table->unsignedBigInteger("target_user_id")->nullable();
            $table->foreign("target_user_id")->references("id")->on("users")->onDelete("cascade");

            $table->text("reason")->nullable();

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
        Schema::dropIfExists('reports');
    }
}
