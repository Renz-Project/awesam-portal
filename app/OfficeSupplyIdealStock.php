<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class OfficeSupplyIdealStock extends Model implements Auditable
{
    //
     use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'office_supply_id',
        'location_id',
        'ideal_stock',
    ];
     public function officeSupply()
    {
        return $this->belongsTo(OfficeSupply::class, 'office_supply_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
