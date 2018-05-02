<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    protected $primaryKey = 'user_id';
    protected $table = 'branch_user';

    protected $fillable = ['branch_id', 'user_id'];

    public function branch(){
        return $this->belongsTo('App\Branch');
    }
}
