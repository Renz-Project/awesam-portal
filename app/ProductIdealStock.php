<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ProductIdealStock extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['product_id', 'location_id', 'ideal_stock'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
