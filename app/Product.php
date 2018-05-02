<?php

namespace App;

class Product extends TogglableModel
{
    protected $fillable = [
        'name', 'category_id',
        'avatar', 'description',
        'price', 'size',
        'barcode', 'created_by'
    ];

    protected $hidden = ['created_by', 'updated_at'];

    //Scope functions
    public function scopeInCategory($query, $category){
        return $query->whereHas('category',
            function ($query) use ($category){
                return $query->whereId($category);
            });
    }

    //Mutators and Accessors
    public function getPriceAttribute($value){
        return number_format($value);
    }

    //Relationships
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function creator(){
        return $this->belongsTo('App\User', 'created_by');
    }
}
