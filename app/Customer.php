<?php

namespace App;

use Webpatser\Uuid\Uuid;

class Customer extends TogglableModel
{
    protected $fillable = ['name', 'phone'];

    public $incrementing = false;

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }

    //Add UUID as Id when creating user
    public static function boot()
    {
        self::creating(function ($user) {
            $user->id = (string) Uuid::generate(4);
            return $user;
        });
    }
}
