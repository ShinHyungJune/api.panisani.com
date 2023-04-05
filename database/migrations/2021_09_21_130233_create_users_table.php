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

            $table->string("ids")->nullable(); // 아이디
            $table->string("nickname")->nullable(); // 이름
            $table->string("name")->nullable(); // 이름
            $table->string("sex")->nullable(); // 성별
            $table->string("birth")->nullable(); // 출생년도
            $table->string("contact")->nullable(); // 연락처
            $table->string("email")->nullable(); // 이메일

            $table->string("address")->nullable();
            $table->string("address_detail")->nullable();
            $table->string("address_zipcode")->nullable();

            $table->string("ids_recommend")->nullable(); // 추천인 아이디
            $table->text("reason_leave_out")->nullable(); // 탈퇴사유

            $table->timestamp('verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("social_id")->nullable();
            $table->string("social_platform")->nullable();
            $table->unique(["social_id", "social_platform"]);

            $table->integer("point")->default(0);

            $table->boolean("agree_privacy")->default(1);
            $table->boolean("agree_marketing")->default(0);
            $table->boolean("agree_usage")->default(1);

            $table->boolean("admin")->default(false);
            $table->boolean("accepted")->default(false);
            $table->boolean("master")->default(false);

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
