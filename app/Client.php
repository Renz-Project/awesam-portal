<?php

namespace App;
use \OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
}
