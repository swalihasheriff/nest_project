<?php

namespace App\Http\Controllers;

use App\Http\Requests\StocktakeItemRequest\StoreStocktakeItemRequest;
use App\Http\Requests\StocktakeItemRequest\UpdateStocktakeItemRequest;
use App\Models\Product;
use App\Models\Stocktake;
use Illuminate\Http\Request;
use App\Models\StocktakeItem;
use Yajra\DataTables\Facades\DataTables;

class StocktakeItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index($stocktake_id)
    {
        if (request()->ajax()) {

            $stocktake = Stocktake::findOrFail($stocktake_id); // ✅ FIX

            $items = StocktakeItem::with('product')
                ->where('stocktake_id', $stocktake_id)
                ->latest();

            $table = DataTables::of($items)

                ->addIndexColumn()

                ->addColumn('name', fn($row) => $row->name ?? '-')

                ->addColumn('barcode', fn($row) => $row->barcode ?? '-')

                ->addColumn('stock_on_hand', fn($row) => $row->stock_on_hand ?? 0)

                ->addColumn('count', fn($row) => $row->count ?? 0)

                ->addColumn('variance', function ($row) {
                    return ($row->count ?? 0) - ($row->stock_on_hand ?? 0);
                })

                ->addColumn('notes', fn($row) => $row->notes ?? '-');


            if ($stocktake->status == 0) {
                $table->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-primary editItemBtn"
                        data-id="' . $row->id . '"
                        data-count="' . $row->count . '"
                        data-notes="' . $row->notes . '">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button class="btn btn-sm btn-danger deleteItemBtn"
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i>
                    </button>
                ';
                });
            }

            return $table
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function fillData($stocktake_id)
    {
        $stocktake = Stocktake::findOrFail($stocktake_id);

        if ($stocktake->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Already finalised'
            ]);
        }

        $products = Product::where('status', 1)->get();

        foreach ($products as $product) {

            StocktakeItem::create([
                'stocktake_id' => $stocktake_id,
                'product_id' => $product->id,
                'name' => $product->description,
                'barcode' => $product->product_barcode1
                    ?? $product->product_barcode2
                    ?? $product->product_barcode3
                    ?? $product->upc
                    ?? $product->ctn_barcode,
                'stock_on_hand' => $product->stock_on_hand ?? 0,
                'count' => 0,
                'notes' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Stocktake filled and finalised'
        ]);
    }

    public function finalize($stocktake_id)
    {
        $stocktake = Stocktake::findOrFail($stocktake_id);

        if ($stocktake->items()->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No items to finalize'
            ]);
        }

        $stocktake->status = 1;
        $stocktake->save();

        return response()->json([
            'status' => true,
            'message' => 'Stocktake finalised'
        ]);
    }
    // public function undoFinalize($stocktake_id)
    // {
    //     $stocktake = Stocktake::findOrFail($stocktake_id);

    //     $stocktake->status = 0;
    //     $stocktake->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Finalization undone'
    //     ]);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStocktakeItemRequest $request, $stocktake_id)
    {
        $stocktake = Stocktake::findOrFail($stocktake_id);

        if ($stocktake->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot add items after finalization'
            ]);
        }
        $product = Product::where('ctn_barcode', $request->barcode)
            ->orWhere('upc', $request->barcode)
            ->orWhere('product_barcode1', $request->barcode)
            ->orWhere('product_barcode2', $request->barcode)
            ->orWhere('product_barcode3', $request->barcode)
            ->first();
        $exists = StocktakeItem::where('stocktake_id', $stocktake_id)
            ->where('product_id', $product->id)
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Item already exists'
            ]);
        }

        StocktakeItem::create([
            'stocktake_id' => $stocktake_id,
            'product_id' => $product->id,
            'name' => $product->description,
            'barcode' => $request->barcode,
            'stock_on_hand' => $product->stock_on_hand ?? 0,
            'count' => $request->count,
            'notes' => $request->notes
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item added successfully'
        ]);

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(UpdateStocktakeItemRequest $request, $stocktakeId, $itemId)
    {
        $stocktake = Stocktake::findOrFail($stocktakeId);

        if ($stocktake->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot edit finalized stocktake'
            ], 422);
        }

        $item = StocktakeItem::where('stocktake_id', $stocktakeId)
            ->where('id', $itemId)
            ->firstOrFail();

        $item->count = $request->count;
        $item->notes = $request->notes;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = StocktakeItem::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }

        $stocktake = Stocktake::findOrFail($item->stocktake_id);

        if ($stocktake->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete finalized stocktake'
            ]);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }
}
