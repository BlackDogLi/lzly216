<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tags_name')->nullable();
            $table->string('tags_flag')->nullable();
            $table->timestamps();
        });
        Schema::create('articles_tags', function (Blueprint $table) {
            $table->integer('articles_id')->unsigned()->index();
            $table->foreign('articles_id')->references('id')->on('articles')->onDelete('cascade');   //外键
            $table->integer('tags_id')->unsigned()->index();
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');   //外键
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
        Schema::dropIfExists('tags');
    }
}
