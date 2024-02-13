<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoundsNumberToLuckyGiftScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lucky_gift_scores', function (Blueprint $table) {
            $table->integer('rounds_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lucky_gift_scores', function (Blueprint $table) {
            $table->dropColumn('rounds_number');
        });
    }
}
