<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
}
