<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'description',
        'supplier_id',

        // Barcodes
        'ctn_barcode',
        'upc',
        'product_barcode1',
        'product_barcode2',
        'product_barcode3',

        // Codes
        'product_code',
        'supplier_code',

        // Prices
        'ctn_cost_price',
        'gst',
        'ctn_sell_price',
        'sell_price2',
        'sell_price3',
        'sell_price4',
        'sell_price5',

        // Stock
        'stock_on_hand',
        'minimum_threshold',
        'shelf_capacity',

        // Location & size
        'location',
        'weight',
        'length',
        'uom',

        // Images
        'images',

        // Flags
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