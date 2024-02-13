<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContributionCoulmnToCurrencyWorthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_worths', function (Blueprint $table) {
            $table->integer('contribution')->after('charisma')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_worths', function (Blueprint $table) {
            $table->dropColumn('contribution');
        });
    }
}
