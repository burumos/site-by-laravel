<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNicoRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nico_ranks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('nico_item_id');
            $table->integer('kind');
            $table->integer('rank');
            $table->date('rank_date');
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
        Schema::dropIfExists('nico_ranks');
    }
}
