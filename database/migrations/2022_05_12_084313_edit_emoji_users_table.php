<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EditEmojiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emoji_users', function (Blueprint $table) {
            $table->dropColumn('to_user_id');
            $table->dropColumn('from_user_id');
            $table->unsignedBigInteger('user_id')->nullable();
        });
        DB::table('emoji_users')->whereNull('user_id')->update(['user_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('user_id');
        $table->unsignedBigInteger('to_user_id')->nullable();
        $table->unsignedBigInteger('from_user_id')->nullable();

    }
}
