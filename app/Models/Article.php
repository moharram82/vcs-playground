<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Author of the article.
     *
     * @return BelongsTo
     */
    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Comments related to the article.
     *
     * @return HasMany
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Users liked the article.
     *
     * @return BelongsToMany
     */
    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')
            ->as('likes')
            ->withTimestamps();
    }

    public function scopePublished(Builder $query)
    {
        $query->where('is_published', 1);
    }

    public function scopeOwnedBy(Builder $query, $ownerId)
    {
        $query->where('user_id', $ownerId);
    }

    public function scopeHasCommentsMoreThan(Builder $query, $amount)
    {
        $query->has('comments', '>', $amount);
    }

    public function scopeCommentContains(Builder $query, $term)
    {
        $query->whereHas('comments', function (Builder $q) use ($term) {
            $q->where('body', 'like', '%' . $term . '%');
        });
    }

    public function scopeHasLikesMoreThan(Builder $query, $amount)
    {
        $query->has('likes', '>', $amount);
    }
}
