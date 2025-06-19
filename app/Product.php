<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
