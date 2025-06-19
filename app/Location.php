<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Location extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class,'location_id','id');
    }
}
