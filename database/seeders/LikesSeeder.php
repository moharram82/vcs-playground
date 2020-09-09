<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $likes = [
            ['article_id' => 1, 'user_id' => 2],
            ['article_id' => 1, 'user_id' => 3],
            ['article_id' => 2, 'user_id' => 3],
            ['article_id' => 3, 'user_id' => 1],
            ['article_id' => 3, 'user_id' => 3],
            ['article_id' => 4, 'user_id' => 1],
            ['article_id' => 6, 'user_id' => 2],
        ];

        foreach ($likes as $like) {
            $article = \App\Models\Like::create($like);
        }
    }
}
