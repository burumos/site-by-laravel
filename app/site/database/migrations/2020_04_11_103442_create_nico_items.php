<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNicoItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nico_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->integer('nico_mylist_id');
            $table->string('video_time', 11);
            $table->string('video_id', 16);
            $table->string('image_src', 255);
            $table->datetime('published_at');
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
        Schema::dropIfExists('nico_items');
    }
}
