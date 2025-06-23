<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model  implements Auditable
{
    //
     use \OwenIt\Auditing\Auditable;
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
