<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory()->count(10)->create();

        $users = [
            ['id' => 1, 'name' => 'Mohammed Moharram', 'email' => 'moharram82@hotmail.com', 'password' => bcrypt('12345678'), 'email_verified_at' => now()],
            ['id' => 2, 'name' => 'Bakri Moharram', 'email' => 'bakri@gmail.com', 'password' => bcrypt('12345678'), 'email_verified_at' => now()],
            ['id' => 3, 'name' => 'Osman Moharram', 'email' => 'osman@gmail.com', 'password' => bcrypt('12345678'), 'email_verified_at' => now()],
        ];

        foreach ($users as $user) {
            $u = \App\Models\User::create($user);
        }
    }
}
