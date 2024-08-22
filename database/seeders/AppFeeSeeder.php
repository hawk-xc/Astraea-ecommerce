<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AppFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appfee = [
            'fee_amount'  => '5000',
            'created_by' => 'developer',
            'updated_by' => 'developer'
        ];

        DB::table('app_fees')->insert($appfee);

    }
}
