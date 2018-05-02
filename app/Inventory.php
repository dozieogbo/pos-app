<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    public function scopeInBranch($query, $branch, $id){
        return $query->where('branch_id', $branch)->where('product_id', $id);
    }
}
