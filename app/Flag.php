<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    public $incrementing = false;

    protected $casts = [
        'value' => 'boolean'
    ];
}
