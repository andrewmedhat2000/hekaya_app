<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomMediaStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_media_stream', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('room_sid')->nullable();
            $table->string('room_name')->nullable();
            $table->string('media_sid')->nullable();
            $table->string('stream_sid')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_media_stream');
    }
}
