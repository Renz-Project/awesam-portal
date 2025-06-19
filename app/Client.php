<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

   public function locations()
    {
      return $this->belongsToMany(Location::class, 'client_locations');
    }

    public function attachments()
    {
      return $this->hasMany(ClientAttachment::class);
    }
}
