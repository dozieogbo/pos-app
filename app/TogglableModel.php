<?php
/**
 * Created by PhpStorm.
 * User: Dozie
 * Date: 4/25/2018
 * Time: 2:42 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class TogglableModel extends Model
{
    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query){
        return $query->where('is_active', true);
    }
}