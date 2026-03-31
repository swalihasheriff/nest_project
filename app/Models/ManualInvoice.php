<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualInvoice extends Model
{
    protected $fillable = [
        'supplier',
        'invoice_number',
        'invoice_date',
        'received_on',
        'received_by',
        'amount',
        'rounding',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(ManualInvoiceItem::class, 'manual_invoice_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class,'supplier');
    }
}
