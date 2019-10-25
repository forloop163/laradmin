<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name')->nullable()->comment('权限名称，英文小写');
            $table->string('label')->nullable()->comment('名称');
            $table->string('redirect')->nullable()->comment('重定向地址，在面包屑中点击会重定向去的地址');
            $table->string('path')->nullable()->comment('路径');
            $table->string('meta')->nullable()->comment('icon,title');
            $table->integer('parent')->default(0)->comment('父级id');
            $table->tinyInteger('display')->default(0)->comment('菜单栏内是否展示');
            $table->string('component')->nullable()->comment('组件位置');
            $table->tinyInteger('is_api')->default(0)->comment('Api接口');
            $table->string('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('permissions');
    }
}
