<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualInvoiceItem extends Model
{
    protected $fillable = [
        'manual_invoice_id',
        'product_id',
        'item_description',
        'barcode',
        'item_price',
        'quantity'
    ];

    public function invoice()
    {
        return $this->belongsTo(ManualInvoice::class, 'manual_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
