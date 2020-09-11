<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Friend;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getting_owned_articles()
    {
        $user = User::factory()->create();
        $ownedArticles = Article::factory()->count(5)->create(
            [
                'user_id' => $user->id,
            ]
        );
        $otherArticle = Article::factory()->create();

        $this->assertEquals(5, $user->articles->count());
        $user->articles->each(
            function ($article) use ($ownedArticles) {
                $this->assertTrue($ownedArticles->contains($article));
            }
        );
        $this->assertFalse($ownedArticles->contains($otherArticle));
    }

    /** @test */
    public function getting_liked_articles()
    {
        $user = User::factory()->create();
        $likedArticles = Article::factory()->count(3)->create();
        $otherArticle = Article::factory()->create();
        $likedArticles->each(
            function ($likedArticle) use ($user) {
                Like::factory()->create(
                    [
                        'user_id' => $user->id,
                        'article_id' => $likedArticle->id,
                    ]
                );
            }
        );
        Like::factory()->create(
            [
                'article_id' => $otherArticle->id,
            ]
        );

        $foundArticles = $user->likes;

        $this->assertEquals(3, $user->likes->count());
        $foundArticles->each(
            function ($foundArticle) use ($likedArticles) {
                $this->assertTrue($likedArticles->contains($foundArticle));
            }
        );
        $this->assertFalse($foundArticles->contains($otherArticle));
    }

    /** @test */
    public function getting_friends()
    {
        $user = User::factory()->create();
        $friendsInvited = User::factory()->count(3)->create();
        $friendsRequested = User::factory()->count(5)->create();
        $notAFriend = User::factory()->create();
        $friendsInvited->each(
            function ($friend) use ($user) {
                Friend::factory()->accepted()->create(
                    [
                        'requester_id' => $user->id,
                        'invited_id' => $friend->id,
                    ]
                );
            }
        );
        $friendsRequested->each(
            function ($friend) use ($user) {
                Friend::factory()->accepted()->create(
                    [
                        'requester_id' => $friend->id,
                        'invited_id' => $user->id,
                    ]
                );
            }
        );
        Friend::factory()->accepted()->create(
            [
                'invited_id' => $notAFriend->id,
            ]
        );

        $friendsOf = $user->friendsOf;
        $friendsOfMine = $user->friendsOfMine;

        $this->assertEquals(3, $friendsOfMine->count());
        $this->assertEquals(4, $friendsOf->count());
    }
}
