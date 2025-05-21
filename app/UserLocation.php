<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class UserLocation extends Model implements Auditable
{
    //
     use \OwenIt\Auditing\Auditable;
     
     public function location()
     {
        return $this->belongsTo(Location::class);
     }
}
