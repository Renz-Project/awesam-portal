<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ExpenseAttachment extends Model implements Auditable
{
    //
     use \OwenIt\Auditing\Auditable;

     public function expense()
     {
        $this->belongsTo(Expense::class);
     }
}
