<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_rankings', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->integer("rank_current")->nullable(); // 현재순위
            $table->integer("rank_prev")->nullable(); // 이전순위
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
        Schema::dropIfExists('search_rankings');
    }
}
