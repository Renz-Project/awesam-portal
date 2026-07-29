<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeSupply extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable, SoftDeletes;
    public function stockMovements()
    {
        return $this->hasMany(StockMovementOffice::class);
    }
    public function category()
    {
        return $this->belongsTo(OfficeCategory::class);
    }
   public function idealStocks()
    {
        return $this->hasMany(OfficeSupplyIdealStock::class, 'office_supply_id');
    }
}
