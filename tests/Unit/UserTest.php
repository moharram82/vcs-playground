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
    public function getting_requested_friends()
    {
        $user = User::factory()->create();
        $requestedFriends = User::factory()->count(5)->create();
        $notAFriendRequested = User::factory()->create();
        $notAFriendInvitedBy = User::factory()->create();
        $requestedFriends->each(
            function ($friend) use ($user) {
                Friend::factory()->accepted()->create(
                    [
                        'requester_id' => $user->id,
                        'invited_id' => $friend->id,
                    ]
                );
            }
        );

        Friend::factory()->accepted()->create(
            [
                'requester_id' => $notAFriendRequested->id,
            ]
        );

        Friend::factory()->accepted()->create(
            [
                'invited_id' => $notAFriendInvitedBy->id,
            ]
        );

        $requestedFriendsFound = $user->requestedFriends;
        $invitedByFriendsFound = $user->invitedByFriends;
        $friends = $user->friends;

        $this->assertEquals(5, $requestedFriendsFound->count());
        $this->assertEquals(0, $invitedByFriendsFound->count());

        $requestedFriendsFound->each(
            function ($friend) use ($requestedFriends, $friends) {
                $this->assertTrue($requestedFriends->contains($friend));
                $this->assertTrue($friends->contains($friend));
            }
        );

        $invitedByFriendsFound->each(
            function ($friend) use ($friends) {
                $this->assertTrue($friends->contains($friend));
            }
        );

        $this->assertFalse($invitedByFriendsFound->contains($notAFriendRequested));
        $this->assertFalse($requestedFriendsFound->contains($notAFriendInvitedBy));
    }

    /** @test */
    public function getting_invited_by_friends()
    {
        $user = User::factory()->create();
        $invitedByFriends = User::factory()->count(5)->create();
        $notAFriendRequested = User::factory()->create();
        $notAFriendInvitedBy = User::factory()->create();
        $invitedByFriends->each(
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
                'requester_id' => $notAFriendRequested->id,
            ]
        );

        Friend::factory()->accepted()->create(
            [
                'invited_id' => $notAFriendInvitedBy->id,
            ]
        );

        $invitedByFriendsFound = $user->invitedByFriends;
        $requestedFriendsFound = $user->requestedFriends;
        $friends = $user->friends;

        $this->assertEquals(5, $invitedByFriendsFound->count());
        $this->assertEquals(0, $requestedFriendsFound->count());

        $invitedByFriendsFound->each(
            function ($friend) use ($invitedByFriends, $friends) {
                $this->assertTrue($invitedByFriends->contains($friend));
                $this->assertTrue($friends->contains($friend));
            }
        );

        $requestedFriendsFound->each(
            function ($friend) use ($friends) {
                $this->assertTrue($friends->contains($friend));
            }
        );

        $this->assertFalse($invitedByFriendsFound->contains($notAFriendRequested));
        $this->assertFalse($requestedFriendsFound->contains($notAFriendInvitedBy));
    }

    /** @test */
    public function getting_friend_requests_sent()
    {
        $user = User::factory()->create();
        $requestsSent = Friend::factory()
            ->request()
            ->count(5)
            ->create(
                [
                    'requester_id' => $user->id,
                ]
            );
        $otherRequest = Friend::factory()->request()->create();

        $requestsSentFound = $user->friendRequestsSent;
        $requestsReceivedFound = $user->friendRequestsReceived;
        $requests = $user->requests;

        $this->assertEquals(5, $requestsSentFound->count());
        $this->assertEquals(0, $requestsReceivedFound->count());

        $requestsSentFound->each(
            function ($request) use ($requestsSent, $requests) {
                $this->assertTrue($requestsSent->contains($request->user));
                $this->assertTrue($requests->contains($request));
            }
        );

        $this->assertFalse($requestsSentFound->contains(
            function ($request) use ($otherRequest) {
                $this->assertFalse($otherRequest->is($request->user));
            }
        ));

        $this->assertFalse($requests->contains(
            function ($request) use ($otherRequest) {
                $this->assertFalse($otherRequest->is($request->user));
            }
        ));
    }

    /** @test */
    public function getting_friend_requests_received()
    {
        $user = User::factory()->create();
        $requestsReceived = Friend::factory()
            ->request()
            ->count(5)
            ->create(
                [
                    'invited_id' => $user->id,
                ]
            );
        $otherRequest = Friend::factory()->request()->create();

        $requestsReceivedFound = $user->friendRequestsReceived;
        $requestsSentFound = $user->friendRequestsSent;
        $requests = $user->requests;

        $this->assertEquals(5, $requestsReceivedFound->count());
        $this->assertEquals(0, $requestsSentFound->count());

        $requestsReceivedFound->each(
            function ($request) use ($requestsReceived, $requests) {
                $this->assertTrue($requestsReceived->contains($request->user));
                $this->assertTrue($requests->contains($request));
            }
        );

        $this->assertFalse($requestsReceivedFound->contains(
            function ($request) use ($otherRequest) {
                $this->assertFalse($otherRequest->is($request->user));
            }
        ));

        $this->assertFalse($requests->contains(
            function ($request) use ($otherRequest) {
                $this->assertFalse($otherRequest->is($request->user));
            }
        ));
    }
}
