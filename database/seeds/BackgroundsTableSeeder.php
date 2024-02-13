<?php

use Illuminate\Database\Seeder;
use App\Models\Background;


class BackgroundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Background::class, 120)->create();
    }
}
