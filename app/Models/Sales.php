<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'invoice_no',
        'account_id',
        'status',
        'reference',
        'type',
        'delivery_date',
        'delivery_address',
        'round',
        'payment_mode',
        'total_amount',
        'finalized_at'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(SalesItem::class, 'sale_id');
    }

}
