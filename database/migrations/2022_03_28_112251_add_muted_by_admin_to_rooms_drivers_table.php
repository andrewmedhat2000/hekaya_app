<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMutedByAdminToRoomsDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms_drivers', function (Blueprint $table) {
            $table->tinyInteger('muted_by_admin')->default(0)->after('muted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms_drivers', function (Blueprint $table) {
            $table->dropColumn('muted_by_admin');
        });
    }
}
