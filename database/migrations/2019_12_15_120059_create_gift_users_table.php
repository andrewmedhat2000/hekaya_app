<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('gift_id')->unsigned()->nullable();
            $table->foreign('gift_id')->references('id')->on('gifts')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->bigInteger('from_user_id')->unsigned()->nullable();
            $table->foreign('from_user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->bigInteger('to_user_id')->unsigned()->nullable();
            $table->foreign('to_user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->tinyInteger('status')->nullable()->default(0);

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
        Schema::dropIfExists('gift_users');
    }
}
