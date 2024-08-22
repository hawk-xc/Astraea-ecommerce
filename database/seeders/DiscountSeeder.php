<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $NewCostumerDisc = [
            'id' => 'DIS-20240000000000000001',
            'title' => 'Diskon Pelanggan Baru',
            'discount_amount' => '1',
            'code_discount' => 'NEWCOSTMR',
            'description_discount' => 'ini Diskon untuk pengguna baru',
            'category_discount' => 'NEW',
            'is_active' => '1',
            'created_by' => 'Developer',
        ];

        DB::table('discounts')->insert($NewCostumerDisc);

    }
}
