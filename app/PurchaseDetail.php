<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = ['purchase_id', 'product_id', 'quantity', 'price'];
    protected $hidden = ['created_at', 'updated_at'];

    public function purchase(){
        return $this->belongsTo('App\Purchase');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
