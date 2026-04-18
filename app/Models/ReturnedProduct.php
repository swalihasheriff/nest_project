<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedProduct extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'goods_receiving_id',
        'manual_invoice_id',
        'invoice_number',
        'status'
    ];

    public function goodsReceiving()
    {
        return $this->belongsTo(GoodsReceiving::class);
    }

    public function manualInvoice()
    {
        return $this->belongsTo(ManualInvoice::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class, 'return_id', 'id');
    }
}
