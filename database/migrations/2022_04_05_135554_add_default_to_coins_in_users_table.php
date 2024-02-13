<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDefaultToCoinsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->whereNull('diamonds')->update(['diamonds' => 0]);
        DB::table('users')->whereNull('contribution')->update(['contribution' => 0]);
        DB::table('users')->whereNull('gems')->update(['gems' => 0]);
        DB::table('users')->whereNull('charisma')->update(['charisma' => 0]);

        Schema::table('users', function (Blueprint $table) {
            $table->integer('diamonds')->unsigned()->nullable(false)->default(0)->change();
            $table->integer('contribution')->unsigned()->nullable(false)->default(0)->change();
            $table->integer('gems')->unsigned()->nullable(false)->default(0)->change();
            $table->integer('charisma')->unsigned()->nullable(false)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('diamonds')->nullable()->default(null)->change();
            $table->integer('contribution')->nullable()->default(null)->change();
            $table->integer('gems')->nullable()->default(null)->change();
            $table->integer('charisma')->nullable()->default(null)->change();
        });
    }
}
