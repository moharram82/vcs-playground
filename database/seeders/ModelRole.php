<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;

class ModelRole extends Model
{

    protected $table = 'model_has_roles';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [];

    public $timestamps = false;
}
