<?php

namespace App;

class Purchase extends TogglableModel
{
    protected $fillable = ['customer_id', 'created_by', 'branch_id', 'is_active', 'amount_paid', 'payment_type_id' ];
    protected $hidden = ['created_by', 'updated_at','customer_id', 'branch_id'];

    public function details(){
        return $this->hasMany('App\PurchaseDetail');
    }

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function branch(){
        return $this->belongsTo('App\Branch');
    }

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }
}
