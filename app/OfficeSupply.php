<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeSupply extends Model
{
    //
    public function stockMovements()
    {
        return $this->hasMany(StockMovementOffice::class);
    }
    public function category()
    {
        return $this->belongsTo(OfficeCategory::class);
    }
}
