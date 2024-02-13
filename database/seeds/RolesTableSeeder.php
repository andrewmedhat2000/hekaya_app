<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "{adding roles}  \r\n";
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'super_admin',
                'guard_name' => 'web',
            ],
            [
                'id' => 2,
                'name' => 'admin',
                'guard_name' => 'web',
            ],
            [
                'id' => 4,
                'name' => 'client',
                'guard_name' => 'web',
            ],
            [
                'id' => 5,
                'name' => 'streamer_admin',
                'guard_name' => 'web',
            ],
            [
                'id' => 6,
                'name' => 'streamer',
                'guard_name' => 'web',
            ],
            [
                'id' => 7,
                'name' => 'distributor',
                'guard_name' => 'web',
            ],
        ]);
    }
}
