<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['role_id' => 1, 'model_type' => 'App\Models\User', 'model_id' => 1],
            ['role_id' => 1, 'model_type' => 'App\Models\User', 'model_id' => 2],
            ['role_id' => 1, 'model_type' => 'App\Models\User', 'model_id' => 3],
        ];

        foreach ($roles as $role) {
            $r = ModelRole::create($role);
        }
    }
}
