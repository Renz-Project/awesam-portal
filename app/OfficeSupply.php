<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class OfficeSupply extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    public function stockMovements()
    {
        return $this->hasMany(StockMovementOffice::class);
    }
    public function category()
    {
        return $this->belongsTo(OfficeCategory::class);
    }
}
