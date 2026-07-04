<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class StockMovementOffice extends Model implements Auditable
{
    //
      use \OwenIt\Auditing\Auditable;
      protected $fillable = ['remarks', 'quantity', 'type'];

    public function officeSupply()
    {
        return $this->belongsTo(OfficeSupply::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
