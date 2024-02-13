<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guess_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('challenger_id');
            $table->unsignedBigInteger('challenger_shape_id'); // Challenger Guess
            $table->unsignedBigInteger('challenged_id')->nullable();
            $table->unsignedBigInteger('challenged_shape_id')->nullable(); // Challenged Guess
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('winner_id')->nullable();
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
        Schema::dropIfExists('guess_game');
    }
}
