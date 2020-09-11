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
    public function invited_friends_are_friends_who_invited_to_join_friendship()
    {
        $user = User::factory()->create();
        $invitedFriends = User::factory()->count(5)->create();
        $notAnInvitedFriend = User::factory()->create();
        $invitedFriends->each(
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
                'requester_id' => $notAnInvitedFriend->id,
            ]
        );

        $invitedFriendsFound = $user->invitedFriends;

        $this->assertEquals(5, $invitedFriendsFound->count());

        $invitedFriendsFound->each(
            function ($friend) use ($invitedFriends) {
                $this->assertTrue($invitedFriends->contains($friend));
            }
        );

        $this->assertFalse($invitedFriendsFound->contains($notAnInvitedFriend));
    }

    /** @test */
    public function invited_by_friends_are_friends_who_requests_friendship()
    {
        $user = User::factory()->create();
        $invitedByFriends = User::factory()->count(5)->create();
        $notAnInvitedByFriend = User::factory()->create();
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
                'invited_id' => $notAnInvitedByFriend->id,
            ]
        );

        $invitedByFriendsFound = $user->invitedByFriends;

        $this->assertEquals(5, $invitedByFriendsFound->count());

        $invitedByFriendsFound->each(
            function ($friend) use ($invitedByFriends) {
                $this->assertTrue($invitedByFriends->contains($friend));
            }
        );

        $this->assertFalse($invitedByFriendsFound->contains($notAnInvitedByFriend));
    }

    /** @test */
    public function sent_friend_requests_are_requests_where_user_requests_friendship()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $sentFriendRequests = Friend::factory()
            ->request()
            ->count(5)
            ->create(
                [
                    'requester_id' => $user->id,
                ]
            );
        $otherUserRequest = Friend::factory()->request()->create(
            [
                'requester_id' => $otherUser->id,
            ]
        );

        $sentFriendRequestsFound = $user->sentFriendRequests;

        $this->assertEquals(5, $sentFriendRequestsFound->count());

        $sentFriendRequestsFound->each(
            function ($request) use ($sentFriendRequests) {
                $this->assertTrue($sentFriendRequests->contains($request->user));
            }
        );

        $this->assertFalse($sentFriendRequestsFound->contains(
            function ($request) use ($otherUserRequest) {
                $this->assertFalse($otherUserRequest->is($request->user));
            }
        ));
    }

    /** @test */
    public function received_friend_requests_are_requests_where_user_invited_to_join_friendship()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $receivedFriendRequests = Friend::factory()
            ->request()
            ->count(5)
            ->create(
                [
                    'invited_id' => $user->id,
                ]
            );
        $otherUserRequest = Friend::factory()->request()->create(
            [
                'invited_id' => $otherUser->id,
            ]
        );

        $receivedFriendRequestsFound = $user->receivedFriendRequests;

        $this->assertEquals(5, $receivedFriendRequestsFound->count());

        $receivedFriendRequestsFound->each(
            function ($request) use ($receivedFriendRequests) {
                $this->assertTrue($receivedFriendRequests->contains($request->user));
            }
        );

        $this->assertFalse($receivedFriendRequestsFound->contains(
            function ($request) use ($otherUserRequest) {
                $this->assertFalse($otherUserRequest->is($request->user));
            }
        ));
    }
    
    /** @test */
    public function friends_includes_invited_friends_and_invited_by_friends()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $invitedFriend = Friend::factory()->accepted()->create(
            [
                'requester_id' => $user->id,
            ]
        );

        $invitedByFriend = Friend::factory()->accepted()->create(
            [
                'invited_id' => $user->id,
            ]
        );

        $otherUserInvitedFriend = Friend::factory()->accepted()->create(
            [
                'requester_id' => $otherUser->id,
            ]
        );

        $otherUserInvitedByFriend = Friend::factory()->accepted()->create(
            [
                'invited_id' => $otherUser->id,
            ]
        );

        $friendsFound = $user->friends;

        $this->assertTrue($friendsFound->contains(function ($friend) use ($invitedFriend) {
            return $friend->id == $invitedFriend->invited_id;
        }));
        $this->assertTrue($friendsFound->contains(function ($friend) use ($invitedByFriend) {
            return $friend->id == $invitedByFriend->requester_id;
        }));

        $this->assertFalse($friendsFound->contains(function ($friend) use ($otherUserInvitedFriend) {
            return $friend->id == $otherUserInvitedFriend->invited_id;
        }));
        $this->assertFalse($friendsFound->contains(function ($friend) use ($otherUserInvitedByFriend) {
            return $friend->id == $otherUserInvitedByFriend->requester_id;
        }));
    }

    /** @test */
    public function requests_includes_sent_and_received_friend_requests()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $sentFriendRequest = Friend::factory()->request()->create(
            [
                'requester_id' => $user->id,
            ]
        );

        $receivedFriendRequest = Friend::factory()->request()->create(
            [
                'invited_id' => $user->id,
            ]
        );

        $otherUserSentFriendRequest = Friend::factory()->request()->create(
            [
                'requester_id' => $otherUser->id,
            ]
        );

        $otherUserReceivedFriendRequest = Friend::factory()->request()->create(
            [
                'invited_id' => $otherUser->id,
            ]
        );

        $requestsFound = $user->requests;

        $this->assertTrue($requestsFound->contains(function ($friend) use ($sentFriendRequest) {
            return $friend->id == $sentFriendRequest->invited_id;
        }));
        $this->assertTrue($requestsFound->contains(function ($friend) use ($receivedFriendRequest) {
            return $friend->id == $receivedFriendRequest->requester_id;
        }));

        $this->assertFalse($requestsFound->contains(function ($friend) use ($otherUserSentFriendRequest) {
            return $friend->id == $otherUserSentFriendRequest->invited_id;
        }));
        $this->assertFalse($requestsFound->contains(function ($friend) use ($otherUserReceivedFriendRequest) {
            return $friend->id == $otherUserReceivedFriendRequest->requester_id;
        }));
    }
}
