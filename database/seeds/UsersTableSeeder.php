<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "{adding admin} \r\n";
        DB::table('users')->insert([
            'name' => 'admin',
            'user_name' => 'super_admin',
            'phone' => '0123456789',
            'email_verified_at' => Carbon::now(),
            'email' => 'admin@site.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
