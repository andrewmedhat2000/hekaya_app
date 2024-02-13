<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo "Starting DB seedings :  \r\n";
        DB::beginTransaction();
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            AdminRoleSeeder::class,
            SettingSeeder::class,
            CountriesTableSeeder::class,
            CitiesTableSeeder::class,
            CurrenciesTableSeeder::class,

        ]);

        DB::commit();
        echo "DB seedings end \r\n";
    }

  
    
}
