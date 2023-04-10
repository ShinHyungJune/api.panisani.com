<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string("email")->nullable()->unique(); // 이메일
            $table->string("description")->nullable(); // 자기소개
            $table->string("nickname")->nullable()->unique(); // 닉네임
            $table->string("name")->nullable(); // 이름

            $table->string("birth")->nullable(); // 출생년도
            $table->string("contact")->nullable(); // 연락처
            $table->string("sex")->nullable(); // 성별

            $table->text("reason_leave_out")->nullable(); // 탈퇴사유

            $table->timestamp('verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("social_id")->nullable();
            $table->string("social_platform")->nullable();
            $table->unique(["social_id", "social_platform"]);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
