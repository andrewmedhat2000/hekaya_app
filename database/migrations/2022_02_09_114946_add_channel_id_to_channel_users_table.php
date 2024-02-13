<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddChannelIdToChannelUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('channel_users')->truncate();

        Schema::table('channel_users', function (Blueprint $table) {
            $table->unsignedBigInteger('channel_id');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->dropColumn('channel_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channel_users', function (Blueprint $table) {
            $table->dropForeign(['channel_id']);
            $table->dropColumn('channel_id');
            $table->string('channel_name')->nullable();
        });
    }
}
