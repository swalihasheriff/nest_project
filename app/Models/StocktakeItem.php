<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StocktakeItem extends Model
{
    protected $fillable = [
        'stocktake_id',
        'name',
        'barcode',
        'stock_on_hand',
        'count',
        'notes'
    ];

    public function stocktake()
    {
        return $this->belongsTo(Stocktake::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
