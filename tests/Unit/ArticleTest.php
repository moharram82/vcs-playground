<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getting_author()
    {
        $author = User::factory()->create();
        $article = Article::factory()->create(
            [
                'user_id' => $author->id,
            ]
        );

        $this->assertTrue($article->author->is($author));
    }

    /** @test */
    public function getting_comments()
    {
        $article = Article::factory()->create();
        $articleComments = Comment::factory()->count(3)->create(
            [
                'article_id' => $article->id,
            ]
        );
        $otherArticleComment = Comment::factory()->create();

        $this->assertEquals($articleComments->count(), $article->comments->count());
        $article->comments
            ->each(
                function ($comment) use ($articleComments) {
                    $this->assertTrue($articleComments->contains($comment));
                }
            );

        $this->assertFalse($article->comments->contains($otherArticleComment));
    }

    /** @test */
    public function getting_likers()
    {
        $article = Article::factory()->create();
        $likers = User::factory()->count(3)->create();
        $otherArticleLiker = User::factory()->create();
        $likers->each(
            function ($liker) use ($article) {
                Like::factory()->create(
                    [
                        'article_id' => $article->id,
                        'user_id' => $liker->id,
                    ]
                );
            }
        );
        Like::factory()->create(
            [
                'user_id' => $otherArticleLiker->id,
            ]
        );

        $this->assertEquals($likers->count(), $article->likes->count());
        $this->assertFalse($article->likes->contains($otherArticleLiker));
        $article->likes
            ->each(
                function ($liker) use ($likers) {
                    $this->assertTrue(
                        $likers->contains($liker)
                    );
                }
            );
    }

    /** @test */
    public function getting_published_articles()
    {
        $publishedArticles = Article::factory()->published()->count(3)->create();
        $unpublishedArticles = Article::factory()->unpublished()->count(4)->create();

        $publishedArticlesFetched = Article::published()->get();
        $unpublishedArticlesFetched = Article::unpublished()->get();

        $this->assertEquals($publishedArticlesFetched->count(), $publishedArticles->count());
        $this->assertEquals($unpublishedArticlesFetched->count(), $unpublishedArticles->count());

        $publishedArticles->each(
            function ($publishedArticle) use ($publishedArticlesFetched) {
                $this->assertTrue($publishedArticlesFetched->contains($publishedArticle));
            }
        );

        $unpublishedArticles->each(
            function ($unpublishedArticle) use ($unpublishedArticlesFetched) {
                $this->assertTrue($unpublishedArticlesFetched->contains($unpublishedArticle));
            }
        );
    }

    /** @test */
    public function getting_articles_owned_by_an_author()
    {
        $author = User::factory()->create();
        $articlesOwnedByAuthor = Article::factory()->count(3)->create(
            [
                'user_id' => $author->id,
            ]
        );
        $articleOwnedByOtherAuthor = Article::factory()->create();

        $fetchedArticles = Article::ownedBy($author->id)->get();

        $this->assertEquals(3, $fetchedArticles->count());
        $fetchedArticles->each(
            function ($foundArticle) use ($articlesOwnedByAuthor) {
                $this->assertTrue($articlesOwnedByAuthor->contains($foundArticle));
            }
        );
        $this->assertFalse($articlesOwnedByAuthor->contains($articleOwnedByOtherAuthor));
    }

    /** @test */
    public function getting_articles_whit_comments_more_than_specific_amount()
    {
        $articleA = Article::factory()->create([]);
        $articleB = Article::factory()->create([]);
        Comment::factory()->count(11)->create(
            [
                'article_id' => $articleA->id,
            ]
        );
        Comment::factory()->count(10)->create(
            [
                'article_id' => $articleB->id,
            ]
        );

        $fetchedArticles = Article::hasCommentsMoreThan(10)->get();

        $this->assertTrue($fetchedArticles->contains($articleA));
        $this->assertFalse($fetchedArticles->contains($articleB));
    }

    /** @test */
    public function getting_articles_whit_specific_comment_body()
    {
        $articleA = Article::factory()->create([]);
        $articleB = Article::factory()->create([]);
        Comment::factory()->count(11)->create(
            [
                'article_id' => $articleA->id,
                'body' => 'first',
            ]
        );
        Comment::factory()->count(10)->create(
            [
                'article_id' => $articleB->id,
                'body' => 'second',
            ]
        );

        $fetchedArticles = Article::hasCommentContains('first')->get();

        $this->assertTrue($fetchedArticles->contains($articleA));
        $this->assertFalse($fetchedArticles->contains($articleB));
    }

    /** @test */
    public function getting_articles_whit_likes_more_than_specific_amount()
    {
        $articleA = Article::factory()->create([]);
        $articleB = Article::factory()->create([]);
        Like::factory()->count(11)->create(
            [
                'article_id' => $articleA->id,
            ]
        );
        Like::factory()->count(10)->create(
            [
                'article_id' => $articleB->id,
            ]
        );

        $fetchedArticles = Article::hasLikesMoreThan(10)->get();

        $this->assertTrue($fetchedArticles->contains($articleA));
        $this->assertFalse($fetchedArticles->contains($articleB));
    }
}
