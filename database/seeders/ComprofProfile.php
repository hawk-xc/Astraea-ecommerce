<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ComprofProfile extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = [
            'phone_number' => '+6281234567890',
            'whatsapp' => '+6281234567890',
            'email' => 'iniemail@mail.com',
            'id_distric' => '419',
            'address' => 'masukkan alamatnya disini',
            'maps' => "ini adalah alamat di google maps",
            'created_by' => 'developer',
            'updated_by' => 'developer'
        ];

        $about = [
            'title' => 'only',
            'description' => 'only artperience',
            'created_by' => 'developer',
            'updated_by' => 'developer'
        ];

        DB::table('contact_us')->insert($profile);
        DB::table('about_us')->insert($about);

    }
}
