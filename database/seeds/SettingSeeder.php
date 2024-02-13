<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'name' => 'view_banner_price',
                'value' => 1000,
            ],
            [
                'id' => 2,
                'name' => 'turns_number_to_show_prize_moon_gift',
                'value' => 20,
            ],
            [
                'id' => 3,
                'name' => 'prize_value_moon_gift',
                'value' => 2000,
            ],
            [
                'id' => 3,
                'name' => 'app_profit',
                'value' => 0,
            ],
        ]);
    }
}
