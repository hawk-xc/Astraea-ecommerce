<?php

namespace Database\Factories;

use Helper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            [
                'id' => Helper::$role_id,
                'name' => Helper::$role_name,
                'created_by' => 'developer'
            ],
            [
                'id' => Helper::$role_id[1],
                'name' => Helper::$role_name[1],
                'created_by' => 'developer'
            ],
            [
                'id' => Helper::$role_id[2],
                'name' => Helper::$role_name[2],
                'created_by' => 'developer'
            ]
        ];
    }
}
