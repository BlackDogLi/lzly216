<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('set_title')->nullable();
            $table->string('set_name')->nullable();
            $table->text('set_value')->nullable();
            $table->string('set_group')->nullable();
            $table->string('set_remark')->nullable();
            $table->enum('set_status', ['base','extends','hidden'])->default('extends');
            $table->enum('data_type', ['textarea', 'text'])->default('text');
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
        Schema::dropIfExists('setting');
    }
}
