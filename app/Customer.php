<?php

namespace App;

class Customer extends TogglableModel
{
    protected $fillable = ['name', 'phone'];

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }
}
