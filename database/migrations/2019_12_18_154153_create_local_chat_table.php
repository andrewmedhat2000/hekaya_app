<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_chat', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->foreign('channel_id')->references('id')->on('local_channels')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('from_user_id')->unsigned()->nullable();
            $table->foreign('from_user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('message')->nullable();

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
        Schema::dropIfExists('local_chat');
    }
}
