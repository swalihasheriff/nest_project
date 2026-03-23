<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocktake extends Model
{
    protected $fillable = [
        'worksheet_name',
        'type',
        'status'
    ];
    
    public function items()
    {
        return $this->hasMany(StocktakeItem::class);
    }

}
