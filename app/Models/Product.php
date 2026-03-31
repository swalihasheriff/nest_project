<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'description',
        'supplier_id',

        'ctn_barcode',
        'upc',
        'product_barcode1',
        'product_barcode2',
        'product_barcode3',

        'product_code',
        'supplier_code',

        'ctn_cost_price',
        'gst',
        'ctn_sell_price',
        'sell_price2',
        'sell_price3',
        'sell_price4',
        'sell_price5',

        'stock_on_hand',
        'minimum_threshold',
        'shelf_capacity',

        'location',
        'weight',
        'length',
        'uom',

        'images',

        'reorder',
        'status'
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'images'  => 'array',
        'reorder' => 'boolean',
        'status'  => 'boolean',
    ];

    /**
     * Relations
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}