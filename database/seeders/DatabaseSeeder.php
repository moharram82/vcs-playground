<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(10)->create();
        $this->call(UsersSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(LikesSeeder::class);
        $this->call(FriendsSeeder::class);
    }
}
