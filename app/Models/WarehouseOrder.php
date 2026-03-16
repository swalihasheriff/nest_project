<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseOrder extends Model
{
    protected $fillable = [
        'supplier_id',
        'status'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function items()
    {
        return $this->hasMany(WarehouseOrderItem::class);
    }

    public function goodsReceiving()
    {
        return $this->hasOne(GoodsReceiving::class);
    }
}
