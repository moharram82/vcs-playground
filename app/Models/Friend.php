<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friends';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [];
}
