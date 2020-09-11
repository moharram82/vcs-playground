<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getting_related_article()
    {
        $article = Article::factory()->create();
        $comment = Comment::factory()->create(
            [
                'article_id' => $article->id,
            ]
        );

        $this->assertTrue($comment->article->is($article));
    }
}
