<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDiamondsBoughtToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('diamonds_spent', 'diamonds_acquired');
        });

        User::query()->update(['diamonds_acquired' => DB::Raw('diamonds_acquired + diamonds')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::query()->update(['diamonds_acquired' => DB::Raw('diamonds_acquired - diamonds')]);

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('diamonds_acquired', 'diamonds_spent');
        });
    }
}
