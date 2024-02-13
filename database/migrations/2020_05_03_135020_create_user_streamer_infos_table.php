<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStreamerInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_streamers_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('user_streamer_id')->unsigned()->nullable();
            $table->foreign('user_streamer_id')->references('id')->on('users_streamers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('mins')->nullable()->default('0');
            $table->string('gold')->nullable()->default('0');


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
        Schema::dropIfExists('users_streamers_info');
    }
}
