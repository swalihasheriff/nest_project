<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'return_id',
        'item_id',
        'return_qty'
    ];

    public function return()
    {
        return $this->belongsTo(ReturnedProduct::class, 'return_id');
    }

    public function warehouseOrderItem()
    {
        return $this->belongsTo(WarehouseOrderItem::class, 'item_id');
    }

    public function manualInvoiceItem()
    {
        return $this->belongsTo(ManualInvoiceItem::class, 'item_id');
    }
}