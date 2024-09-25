<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        $this->call([RoleSeeder::class]);
        $this->call([ComprofProfile::class]);
        $this->call([DiscountSeeder::class]);
        $this->call([ProvinceSeeder::class]);
        $this->call([DistrictsSeeder::class]);
        $this->call([SubDistrictsSeeder::class]);
        $this->call([AppFeeSeeder::class]);
    }
}
