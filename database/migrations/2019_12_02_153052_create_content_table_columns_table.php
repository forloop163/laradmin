<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTableColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_table_columns', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->integer('content_table_id')->comment('表id');
            $table->string('label')->nullable()->comment('列名, 前端显示');
            $table->string('name')->nullable()->comment('列名, 数据库字段名称');
            $table->string('type')->default('string')->comment('类型');
            $table->string('length')->nullable()->comment('类型长度');
            $table->tinyInteger('allow_null')->default(0)->comment('允许为null');
            $table->string('default')->nullable()->comment('默认值');
            $table->string('comment')->nullable()->comment('备注');
            $table->tinyInteger('list_show')->default(1)->comment('列表是否显示 1显示-0隐藏');
            $table->string('list_item_width')->nullable()->comment('列表字段显示宽度');
            $table->tinyInteger('form_show')->default(1)->comment('表单是否显示 1显示-0隐藏');
            $table->string('form_item_type')->default('input')->comment('form组件类型');
            $table->string('form_item_width')->nullable()->comment('form组件宽度');
            $table->string('form_item_help')->nullable()->comment('form组件help');
            $table->string('form_item_rules', 500)->default('')->comment('验证规则');
            $table->string('options')->nullable()->comment('select选项');
            $table->tinyInteger('is_search')->default(0)->comment('搜索字段 1是-0否');
            $table->tinyInteger('is_sort')->default(0)->comment('是否排序 1是-0否');
            $table->tinyInteger('is_export')->default(0)->comment('是否导出 1是-0否');
            $table->integer('sort')->default(100)->comment('排序，倒序');
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
        Schema::dropIfExists('content_table_columns');
    }
}
