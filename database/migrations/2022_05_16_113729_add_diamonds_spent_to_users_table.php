<?php

use App\Models\GiftSends;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiamondsSpentToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('diamonds_spent')->default(0)->after('contribution');
        });

        GiftSends::selectRaw('from_user_id, sum(price) as price')
        ->groupBy('from_user_id')->get()
        ->each(function($user){
            User::where('id', $user->from_user_id)->update(['diamonds_spent' => $user->price ?? 0]);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('diamonds_spent');
        });
    }
}
