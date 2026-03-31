<?php

namespace App\Http\Controllers;

use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;

class ProductPriceHistoryController extends Controller
{
    public function index($product_id)
    {
        $history = ProductPriceHistory::where('product_id', $product_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $history
        ]);
    }
}
