<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Articles related to the user.
     *
     * @return HasMany
     */
    public function articles() : HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Articles liked by the user.
     *
     * @return BelongsToMany
     */
    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'likes')
            ->as('likes')
            ->withTimestamps();
    }

    /**
     * Friends where the user requested the friendship.
     *
     * @return BelongsToMany
     */
    public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friends', 'requester_id', 'invited_id')
            ->as('friendships')
            ->wherePivot('is_request', false)
            ->withTimestamps();
    }

    /**
     * Friends where the user was invited to the friendship.
     *
     * @return BelongsToMany
     */
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friends', 'invited_id', 'requester_id')
            ->as('friendships')
            ->wherePivot('is_request', false)
            ->withTimestamps();
    }

    /**
     * Query scope to get all friends (friendsOfMine + friendOF).
     *
     * @return mixed
     */
    public function getFriendsAttribute()
    {
        // if there is no 'friends' relationship in the relations array, then set the relationship.
        if(! array_key_exists('friends', $this->relations)) {
            $this->loadFriendsRelation();
        }

        // if there is a 'friends' relationship in the relations array, then return it.
        return $this->getRelation('friends');
    }

    /**
     * Load the friends relationship into the relations array.
     */
    private function loadFriendsRelation()
    {
        $this->setRelation('friends', $this->mergeFriends());
    }

    /**
     * Merge both relations (friendsOfMine + friendOF) and return the combined collection.
     *
     * @return mixed
     */
    private function mergeFriends()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }

    /**
     * Friendship requests sent by the user.
     *
     * @return BelongsToMany
     */
    public function requestsOfMine()
    {
        return $this->belongsToMany(User::class, 'friends', 'requester_id', 'invited_id')
            ->as('requests')
            ->wherePivot('is_request', true)
            ->withTimestamps();
    }

    /**
     * Friendship requests received by the user.
     *
     * @return BelongsToMany
     */
    public function requestsToMe()
    {
        return $this->belongsToMany(User::class, 'friends', 'invited_id', 'requester_id')
            ->as('requests')
            ->wherePivot('is_request', true)
            ->withTimestamps();
    }

    /**
     * Query scope to get all friend requests (requestsOfMine + requestsToMe).
     *
     * @return mixed
     */
    public function getRequestsAttribute()
    {
        // if there is no 'requests' relationship in the relations array, then set the relationship.
        if(! array_key_exists('requests', $this->relations)) {
            $this->loadRequestsRelation();
        }

        // if there is a 'requests' relationship in the relations array, then return it.
        return $this->getRelation('requests');
    }

    /**
     * Load the requests relationship into the relations array.
     */
    private function loadRequestsRelation()
    {
        $this->setRelation('requests', $this->mergeRequests());
    }

    /**
     * Merge both relations (requestsOfMine + requestsToMe) and return the combined collection.
     *
     * @return mixed
     */
    private function mergeRequests()
    {
        return $this->requestsOfMine->merge($this->requestsToMe);
    }
}
