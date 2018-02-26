<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nulltable();   //文章标题
            $table->string('thumb')->default('')->nullable();   //文章缩略图
            $table->string('flag')->unique();   //标记
            $table->integer('category_id')->default('0');   //分类ID
            $table->integer('user_id');     //admin_users表中ID
            $table->text('content')->nullable();    //内容
            $table->text('markdown')->nullable();   //简介
            $table->integer('views')->default('0')->nullable(); //浏览次数
            $table->integer('comments')->default('0')->nullable();  //评论数
            $table->string('ipaddress')->default('0.0.0.0')->nullable();    //地址
            $table->softDeletes();  //软删除
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
        Schema::dropIfExists('articles');
    }
}
