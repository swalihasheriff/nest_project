<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'discount',
        'total_amount'
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    /**
     * Relation with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
