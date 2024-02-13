<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameChannelNameColumnInRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->renameColumn('channel_name', 'channel_sid');
            $table->renameColumn('hoster_id', 'host_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->renameColumn('channel_sid', 'channel_name');
            $table->renameColumn('host_id', 'hoster_id');
        });
    }
}
