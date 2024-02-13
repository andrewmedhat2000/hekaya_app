<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSidColumnFromRoomsDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms_drivers', function (Blueprint $table) {
            $table->dropColumn('sid');
            $table->renameColumn('channel_name', 'channel_sid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms_drivers', function (Blueprint $table) {
            $table->string('sid')->nullable();
            $table->renameColumn('channel_sid', 'channel_name');
        });
    }
}
