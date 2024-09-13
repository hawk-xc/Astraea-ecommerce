<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Slider 1',
                'image' => 'guest/img/slide-home/1.png',
                'button_title' => 'Selengkapnya',
                'button_link' => '#',
                'button_background' => '#fff',
                'button_text_color' => '#000',
                'button_horizontal_layout' => 'left',
                'button_vertical_layout' => 'bottom',
            ],
            [
                'title' => 'Slider 2',
                'image' => 'guest/img/slide-home/2.png',
                'button_title' => 'Selengkapnya',
                'button_link' => '#',
                'button_background' => '#fff',
                'button_text_color' => '#000',
                'button_horizontal_layout' => 'left',
                'button_vertical_layout' => 'bottom',
            ],
            [
                'title' => 'Slider 3',
                'image' => 'guest/img/slide-home/3.png',
                'button_title' => 'Selengkapnya',
                'button_link' => '#',
                'button_background' => '#fff',
                'button_text_color' => '#000',
                'button_horizontal_layout' => 'left',
                'button_vertical_layout' => 'bottom',
            ],
        ];

        DB::table('sliders')->insert($sliders);
    }
}
