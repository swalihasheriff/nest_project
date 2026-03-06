<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseOrderItem extends Model
{
    protected $fillable = [
        'warehouse_order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(WarehouseOrder::class, 'warehouse_order_id');
    }
}
