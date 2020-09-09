<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            ['article_id' => 1, 'user_id' => 2, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 1, 'user_id' => 3, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 2, 'user_id' => 2, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 2, 'user_id' => 2, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 3, 'user_id' => 1, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 3, 'user_id' => 3, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 4, 'user_id' => 3, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 4, 'user_id' => 3, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 5, 'user_id' => 1, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 5, 'user_id' => 1, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
            ['article_id' => 5, 'user_id' => 2, 'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur atque autem consequatur</p>'],
        ];

        foreach ($comments as $comment) {
            $article = \App\Models\Comment::create($comment);
        }
    }
}
