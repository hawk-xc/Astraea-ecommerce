<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 'RL-167267347114',
                'name' => 'admin',
                'description' => 'grant all access',
                'created_by' => 'Dev',
                'created_at' => '2023-10-12 14:56:16',
            ],
            [
                'id' => 'RL-167267347119',
                'name' => 'employee',
                'description' => 'Head chief',
                'created_by' => 'Dev',
                'created_at' => '2023-10-12 14:56:59',
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
