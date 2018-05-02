<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = ['is_arrival', 'product_id', 'branch_id', 'quantity'];

    protected $casts = [
        'is_arrival' => 'boolean'
    ];

    public function scopeOfProduct($query, $product){
        return $query->where('product_id', $product);
    }

    public function scopeArrival($query){
        return $query->where('is_arrival', true);
    }

    public function scopeReturn($query){
        return $query->where('is_arrival', false);
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function branch(){
        return $this->belongsTo('App\Branch');
    }

    public function creator(){
        return $this->belongsTo('App\Creator');
    }

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }
}
