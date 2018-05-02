<?php

namespace App;

class Branch extends TogglableModel
{
    protected $fillable = ['code', 'address', 'state_id', 'created_by', 'is_active'];

    protected $hidden = ['created_at', 'updated_at', 'created_by', 'state_id'];

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }

    public function state(){
        return $this->belongsTo('App\State');
    }

    public function salesTeam(){
        return $this->belongsToMany('App\User', 'branch_user')->hasRole('sales');
    }

    public function supervisors(){
        return $this->belongsToMany('App\User', 'branch_user')->hasRole('supervisor');
    }

    public function staffs(){
        return $this->belongsToMany('App\User', 'branch_user');
    }
}
