<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'Author', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            $r = \Spatie\Permission\Models\Role::create($role);
        }

        $permissions = [
            ['id' => 1, 'name' => 'Create Article', 'guard_name' => 'web']
        ];

        foreach ($permissions as $permission) {
            $p = \Spatie\Permission\Models\Permission::create($permission);
        }

        $author_role = \Spatie\Permission\Models\Role::findByName('Author');
        $article_permission = \Spatie\Permission\Models\Permission::findByName('Create Article');
        $author_role->givePermissionTo($article_permission);
    }
}
