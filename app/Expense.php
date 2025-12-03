<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model implements Auditable
{
    //
     use \OwenIt\Auditing\Auditable;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function attachments()
    {
        return $this->hasMany(ExpenseAttachment::class);
    }
}
