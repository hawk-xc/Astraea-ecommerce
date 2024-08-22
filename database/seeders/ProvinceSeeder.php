<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prov = [
                ["id" => "1","name" => "Bali", 'created_by' => 'developer'],
                ["id" => "2","name" => "Bangka Belitung", 'created_by' => 'developer'],
                ["id" => "3","name" => "Banten", 'created_by' => 'developer'],
                ["id" => "4","name" => "Bengkulu", 'created_by' => 'developer'],
                ["id" => "5","name" => "DI Yogyakarta", 'created_by' => 'developer'],
                ["id" => "6","name" => "DKI Jakarta", 'created_by' => 'developer'],
                ["id" => "7","name" => "Gorontalo", 'created_by' => 'developer'],
                ["id" => "8","name" => "Jambi", 'created_by' => 'developer'],
                ["id" => "9","name" => "Jawa Barat", 'created_by' => 'developer'],
                ["id" => "10","name" => "Jawa Tengah", 'created_by' => 'developer'],
                ["id" => "11","name" => "Jawa Timur", 'created_by' => 'developer'],
                ["id" => "12","name" => "Kalimantan Barat", 'created_by' => 'developer'],
                ["id" => "13","name" => "Kalimantan Selatan", 'created_by' => 'developer'],
                ["id" => "14","name" => "Kalimantan Tengah", 'created_by' => 'developer'],
                ["id" => "15","name" => "Kalimantan Timur", 'created_by' => 'developer'],
                ["id" => "16","name" => "Kalimantan Utara", 'created_by' => 'developer'],
                ["id" => "17","name" => "Kepulauan Riau", 'created_by' => 'developer'],
                ["id" => "18","name" => "Lampung", 'created_by' => 'developer'],
                ["id" => "19","name" => "Maluku", 'created_by' => 'developer'],
                ["id" => "20","name" => "Maluku Utara", 'created_by' => 'developer'],
                ["id" => "21","name" => "Nanggroe Aceh Darussalam (NAD)", 'created_by' => 'developer'],
                ["id" => "22","name" => "Nusa Tenggara Barat (NTB)", 'created_by' => 'developer'],
                ["id" => "23","name" => "Nusa Tenggara Timur (NTT)", 'created_by' => 'developer'],
                ["id" => "24","name" => "Papua", 'created_by' => 'developer'],
                ["id" => "25","name" => "Papua Barat", 'created_by' => 'developer'],
                ["id" => "26","name" => "Riau", 'created_by' => 'developer'],
                ["id" => "27","name" => "Sulawesi Barat", 'created_by' => 'developer'],
                ["id" => "28","name" => "Sulawesi Selatan", 'created_by' => 'developer'],
                ["id" => "29","name" => "Sulawesi Tengah", 'created_by' => 'developer'],
                ["id" => "30","name" => "Sulawesi Tenggara", 'created_by' => 'developer'],
                ["id" => "31","name" => "Sulawesi Utara", 'created_by' => 'developer'],
                ["id" => "32","name" => "Sumatera Barat", 'created_by' => 'developer'],
                ["id" => "33","name" => "Sumatera Selatan", 'created_by' => 'developer'],
                ["id" => "34","name" => "Sumatera Utara", 'created_by' => 'developer']
            ];

        DB::table('provinces')->insert($prov);

    }
}