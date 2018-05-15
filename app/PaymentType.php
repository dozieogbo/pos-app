<?php

namespace App;

class PaymentType extends TogglableModel
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function scopeHasName($query, $name){
        return $query->where('name', 'like', '%'.$name.'%');
    }
}
