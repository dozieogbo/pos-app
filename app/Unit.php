<?php

namespace App;

class Unit extends TogglableModel
{
    protected $fillable = ['quantity', 'name', 'created_by', 'is_active'];
    protected $hidden = ['created_at', 'updated_at', 'created_by'];

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }

}
