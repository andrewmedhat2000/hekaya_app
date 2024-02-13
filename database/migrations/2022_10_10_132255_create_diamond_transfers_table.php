<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamondTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamond_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('diamonds');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diamond_transfers');
    }
}
