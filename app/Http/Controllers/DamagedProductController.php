<?php

namespace App\Http\Controllers;

use App\Http\Requests\DamagedProductRequest\AddDamagedProductRequest;
use App\Http\Requests\DamagedProductRequest\UpdateDamagedProductRequest;
use App\Models\DamagedProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DamagedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = DamagedProduct::with('product');
            if ($request->status !== null && $request->status !== '') {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product_id', function ($row) {
                    return $row->product_id;
                })

                ->addColumn('item_description', function ($row) {
                    return $row->product->description ?? '-';
                })

                ->addColumn('barcode', function ($row) {
                    return $row->product->product_barcode1 ?? '-';
                })

                ->addColumn('quantity', function ($row) {
                    return $row->quantity;
                })

                ->addColumn('action', function ($row) {

                    if ($row->status == 0) {
                        return '
                        <button class="btn btn-sm btn-primary editBtn"
    data-id="' . $row->id . '"
    data-qty="' . $row->quantity . '"
    data-name="' . ($row->product->description ?? '') . '">
    <i class="bi bi-pencil"></i>
</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i>
                        </button>
                    ';
                    }

                    return '
                    <button class="btn btn-sm btn-warning undoBtn" data-id="' . $row->id . '">
                        <i class="bi bi-arrow-counterclockwise"></i> Undo Finalize
                    </button>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        $products = Product::where('status', 1)->get();

        return view('damaged_product.index', compact('products'));
    }

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

    public function store(AddDamagedProductRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock_on_hand < $request->quantity) {
            return response()->json([
                'status' => false,
                'message' => 'Not enough stock'
            ], 422);
        }


        DamagedProduct::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'status' => 1
        ]);


        $product->stock_on_hand -= $request->quantity;
        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Damaged product added successfully'
        ]);
    }

    public function undo($id)
    {
        $damaged = DamagedProduct::findOrFail($id);

        // already initialized → no need
        if ($damaged->status == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Already initialized'
            ]);
        }

        $product = Product::findOrFail($damaged->product_id);

        $product->stock_on_hand += $damaged->quantity;
        $product->save();

        $damaged->status = 0;
        $damaged->save();

        return response()->json([
            'status' => true,
            'message' => 'Undo successful, stock restored'
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

    public function update(UpdateDamagedProductRequest $request, $id)
    {
        $damaged = DamagedProduct::findOrFail($id);
        $product = Product::findOrFail($damaged->product_id);

        if ($damaged->status == 1) {
            $product->stock_on_hand += $damaged->quantity;
        }

        if ($product->stock_on_hand < $request->quantity) {
            return response()->json([
                'status' => false,
                'message' => 'Not enough stock'
            ], 422);
        }

        $product->stock_on_hand -= $request->quantity;
        $product->save();

        $damaged->update([
            'quantity' => $request->quantity,
            'status' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Updated & Finalized successfully'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $damaged = DamagedProduct::find($id);

        if (!$damaged) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }

        if ($damaged->status == 1) {

            $product = Product::find($damaged->product_id);

            if ($product) {
                $product->stock_on_hand += $damaged->quantity;
                $product->save();
            }
        }

        $damaged->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
