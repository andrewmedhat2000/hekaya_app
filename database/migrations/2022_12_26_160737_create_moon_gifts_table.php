<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoonGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moon_gifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('round_number')->unsigned()->nullable(false)->default(0);
            $table->integer('round_value')->unsigned()->nullable(false)->default(0);
            $table->integer('minimum_win')->unsigned()->nullable(false)->default(0);
            $table->integer('maximum_win')->unsigned()->nullable(false)->default(0);
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
        Schema::dropIfExists('moon_gifts');
    }
}
