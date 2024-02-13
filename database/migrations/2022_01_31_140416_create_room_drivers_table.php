<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('sid')->nullable();
            // $table->string('img')->nullable();
            $table->string('status')->nullable();
            $table->timestamps(); //dateCreated - endTime
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms_drivers');
    }
}
