<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForiegnKeysInRoomsAndChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign(['host_id']);
            $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('channel_sid')->references('sid')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign(['host_id']);
            $table->foreign('host_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['channel_sid']);
        });
    }
}
