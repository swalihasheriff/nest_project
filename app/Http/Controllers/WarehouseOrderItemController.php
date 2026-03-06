<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseOrderItemRequest\StoreWarehouseOrderItemRequest;
use App\Http\Requests\WarehouseOrderItemRequest\UpdateWarehouseOrderItemRequest;
use App\Models\Product;
use App\Models\WarehouseOrder;
use App\Models\WarehouseOrderItem;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $orderId)
    {
        if ($request->ajax()) {

            $items = WarehouseOrderItem::with('product')
                ->where('warehouse_order_id', $orderId)
                ->orderBy('created_at', 'asc');

            return DataTables::of($items)
                ->addIndexColumn()

                ->addColumn('description', function ($row) {
                    return $row->product?->description ?? '-';
                })

                ->addColumn('barcode', function ($row) {
                    return $row->product?->product_barcode1 ?? '-';
                })

                ->addColumn('price', function ($row) {
                    return number_format($row->price, 2);
                })

                ->addColumn('quantity', function ($row) {
                    return $row->quantity;
                })

                ->addColumn('total', function ($row) {
                    return number_format($row->total, 2);
                })

                ->addColumn('action', function ($row) {
                    return ' 
                    <button class="btn btn-sm btn-outline-danger delete-item"
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i>
                    </button>

                    <button class="btn btn-sm btn-outline-primary edit-item"
                            data-id="' . $row->id . '"
                            data-qty="' . $row->quantity . '"
                            data-name="' . ($row->product?->description ?? '-') . '">   
                        <i class="bi bi-pencil"></i>
                    </button>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseOrderItemRequest $request, $orderId)
    {

        $order = WarehouseOrder::findOrFail($orderId);

        if (in_array($order->status, ['delivered', 'cancel'])) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot add items to a finalized order'
            ], 422);
        }

        $product = Product::findOrFail($request->product_id);

        $item = new WarehouseOrderItem();
        $item->warehouse_order_id = $order->id;
        $item->product_id = $product->id;
        $item->price = $product->ctn_cost_price;
        $item->quantity = $request->quantity;
        $item->total = $product->ctn_cost_price * $request->quantity;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item added to order successfully.'
        ]);
    }


    // public function autoFill(WarehouseOrder $order)
    // {
    //     try {
    //         $products = Product::where('supplier_id', $order->supplier_id)->get();
    //         foreach ($products as $product) {
    //             $stock = $product->stock_on_hand;
    //             $min = $product->minimum_threshold;
    //             $cap = $product->shelf_capacity ?? 0;

    //             // if ($stock >= $min) {
    //             //     continue;
    //             // }

    //             // if ($stock<0){
    //             //     $qty= $cap;
    //             // }
    //             // else{
    //             //     $qty= $cap - $stock;
    //             // }

    //             if ($cap > 0) {

    //                 if ($stock < 0) {
    //                     $qty = $cap;
    //                 } else {
    //                     $qty = $cap - $stock;
    //                 }


    //                 if (
    //                     WarehouseOrderItem::where('warehouse_order_id', $order->id)
    //                         ->where('product_id', $product->id)
    //                         ->exists()
    //                 ) {
    //                     continue;
    //                 }

    //                 WarehouseOrderItem::create([
    //                     'warehouse_order_id' => $order->id,
    //                     'product_id' => $product->id,
    //                     'quantity' => $qty,
    //                     'price' => $product->ctn_cost_price,
    //                     'total' => $qty * $product->ctn_cost_price,
    //                 ]);
    //                 return response()->json(['status' => true]);

    //             } else {
    //                 return response()->json(['status' => false, 'message' => 'Please add shelf capacity.'], 422);
    //             }
    //         }

    //     } catch (Exception $e) {
    //         report($e);
    //     }
    // }

    public function autoFill(WarehouseOrder $order)
    {
        try {

            $products = Product::where('supplier_id', $order->supplier_id)
                ->where('reorder', true)
                ->get();
            if ($products->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This product cannot be autofilled!'
                ], 422);
            }

            $added = 0;

            foreach ($products as $product) {

                $stock = $product->stock_on_hand ?? 0;
                $min = $product->minimum_threshold ?? 0;
                $cap = $product->shelf_capacity ?? 0;


                if ($stock >= $min) {
                    continue;
                }


                if ($cap > 0) {

                    $qty = $cap - $stock;
                } else {

                    $qty = $min - $stock;
                }


                if ($qty <= 0) {
                    continue;
                }


                if (
                    WarehouseOrderItem::where('warehouse_order_id', $order->id)
                        ->where('product_id', $product->id)
                        ->exists()
                ) {
                    continue;
                }

                WarehouseOrderItem::create([
                    'warehouse_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->ctn_cost_price,
                    'total' => $qty * $product->ctn_cost_price,
                ]);

                $added++;
            }

            if ($added === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No products eligible for auto fill'
                ], 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'Products auto-filled successfully'
            ]);

        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong during auto fill'
            ], 500);
        }
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
    public function update(UpdateWarehouseOrderItemRequest $request, $orderId, $itemId)
    {

        $order = WarehouseOrder::findOrFail($orderId);
        if (in_array($order->status, ['delivered', 'cancel'])) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot edit finalized order'
            ], 422);
        }

        $item = WarehouseOrderItem::where('warehouse_order_id', $orderId)
            ->where('id', $itemId)
            ->firstOrFail();
        $item->quantity = $request->edit_quantity;
        $item->total = $item->price * $request->edit_quantity;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item updated successfully'
        ]);
    }

    /** 
     * Remove the specified resource from storage.
     */
    public function destroy($orderId, $itemId)
    {
        $order = WarehouseOrder::findOrFail($orderId);
        if (in_array($order->status, ['delivered', 'cancel'])) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete items from finalized order'
            ], 422);
        }

        $item = WarehouseOrderItem::where('warehouse_order_id', $orderId)
            ->where('id', $itemId)
            ->firstOrFail();

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }
}
