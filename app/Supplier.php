<?php

namespace App;

class Supplier extends TogglableModel
{
    protected $fillable = ['name', 'code', 'created_by', 'is_active'];

    protected $hidden = ['created_at', 'updated_at', 'created_by'];

    //Relationships
    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }
}
