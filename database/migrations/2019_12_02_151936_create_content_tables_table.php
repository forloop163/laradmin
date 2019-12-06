<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('table_name')->unique()->comment('表名');
            $table->string('desc', 500)->nullable()->comment('描述');
            $table->string('indexes', 500)->nullable()->comment('索引');
            $table->text('relactions')->nullable()->comment('索引');
            $table->integer('created_by')->default(0)->comment('创建人');
            $table->tinyInteger('status')->default(0)->comment('1 已创建表结构');
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
        Schema::dropIfExists('content_tables');
    }
}
