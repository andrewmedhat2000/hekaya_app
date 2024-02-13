<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStreamersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_streamers', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('streamer_id')->unsigned()->nullable();
            $table->foreign('streamer_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
                
            $table->bigInteger('streamer_admin_id')->unsigned()->nullable();
            $table->foreign('streamer_admin_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('cost_per_hour')->nullable()->default('0');

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
        Schema::dropIfExists('users_streamers');
    }
}
