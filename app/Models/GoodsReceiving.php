<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiving extends Model
{
    protected $fillable = [
        'warehouse_order_id',
        'supplier_id',
        'invoice_number',
        'received_by',
        'received_on',
        'status',   
        'finalized_at'
    ];
    public function warehouseOrder()
    {
        return $this->belongsTo(WarehouseOrder::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
