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
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('username')->unique()->comment('用户名称');
            $table->string('email')->default('')->comment('用户邮箱');
            $table->string('mobile')->default('')->comment('手机号码');
            $table->tinyInteger('active')->default(1)->comment('冻结标识');
            $table->timestamp('email_verified_at')->nullable()->comment('邮箱验证相关');
            $table->string('password')->default('')->comment('密码');
            $table->timestamp('last_login')->nullable()->comment('最后登陆时间');
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
