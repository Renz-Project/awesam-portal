<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
       protected $fillable = [
        'product_code',
        'product_name',
        'category_id',
        'unit_price',
    ];
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class,'product_id','id');
    }
    public function idealStocks()
    {
        return $this->hasMany(ProductIdealStock::class, 'product_id');
    }
}
