<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyWorthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_worths', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('price')->nullable();
            $table->string('diamond')->nullable();
            $table->integer('gem')->nullable();            
            $table->string('charisma')->nullable();
            
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
        Schema::dropIfExists('currency_worths');
    }
}
