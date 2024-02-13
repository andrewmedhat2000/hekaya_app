<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDaysToExpireToLoginSignsAndBadgesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_signs', function (Blueprint $table) {
            $table->integer('days_to_expire')->nullable();
        });

        Schema::table('badges', function (Blueprint $table) {
            $table->integer('days_to_expire')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_signs', function (Blueprint $table) {
            $table->dropColumn('days_to_expire')->nullable();
        });
        
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn('days_to_expire')->nullable();
        });
    }
}
