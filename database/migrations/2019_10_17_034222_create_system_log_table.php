<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_log', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->integer('entity_id')->nullable()->comment('操作数据ID');
            $table->integer('user_id')->nullable()->comment('操作人');
            $table->string('log', 500)->default('')->comment('记录');
            $table->string('action')->default('')->comment('操作');
            $table->string('model')->default('')->comment('模型');
            $table->text('data')->nullable()->comment('数据');
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
        Schema::dropIfExists('system_log');
    }
}
