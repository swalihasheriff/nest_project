<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest\StoreOrderRequest;
use App\Models\GoodsReceiving;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\WarehouseOrder;
use App\Models\WarehouseOrderItem;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $orders = WarehouseOrder::with('supplier')
                ->orderByDesc('created_at');

            /* Filters */
            if ($request->supplier_id) {
                $orders->where('supplier_id', $request->supplier_id);
            }

            if ($request->status) {
                $orders->where('status', $request->status);
            }

            return DataTables::of($orders)
                ->addIndexColumn()

                ->addColumn('supplier', function ($row) {
                    return $row->supplier?->name ?? '-';
                })

                ->addColumn('order_status', function ($row) {
                    return match ($row->status) {

                        1 => '<span class="badge bg-secondary">
                        Initiated on ' . $row->created_at->format('d-m-Y h:i A') . '
                    </span>',

                        2 => '<span class="badge bg-primary ">
                        Ordered on ' . $row->updated_at->format('d-m-Y h:i A') . '
                    </span>',

                        4 => '<span class="badge bg-success">
                        Delivered on ' . $row->updated_at->format('d-m-Y h:i A') . '
                    </span>',

                        3 => '<span class="badge bg-danger">
                        Cancelled on ' . $row->updated_at->format('d-m-Y h:i A') . '
                    </span>',

                        default => '-'
                    };
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d-m-Y h:i A')
                        : '-';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex justify-content-center">
                        <a href="' . route('warehouse.orders.view', $row->id) . '"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                ';
                })

                ->rawColumns(['order_status', 'action'])
                ->make(true);
        }
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();

        return view('warehouse_orders.index', compact('suppliers'));
    }

    public function finalize(WarehouseOrder $order)
    {
        $ordereditems = WarehouseOrderItem::where('warehouse_order_id', $order->id)->count();
        if ($ordereditems > 0) {
            $order->status = 2;
            $order->save();
        }
        return response()->json(['status' => true]);
    }

    public function undoFinalize(WarehouseOrder $order)
    {
        $order->status = 1;
        $order->save();

        return response()->json(['status' => true]);
    }

    public function cancel(WarehouseOrder $order)
    {
        $order->status = 3;
        $order->save();


        return response()->json(['status' => true]);
    }

public function deliver(WarehouseOrder $order)
{
    $order->status = 4;
    $order->save();

    GoodsReceiving::create([
        'warehouse_order_id' => $order->id,
        'supplier_id' => $order->supplier_id,
        'status' => 0
    ]);

    return response()->json([
        'status' => true
    ]);
}


    public function updateStock(WarehouseOrder $order)
    {
        if ($order->status !== 2) {
            return response()->json([
                'status' => false,
                'message' => 'Order must be finalized before delivery'
            ], 422);
        }

        DB::transaction(function () use ($order) {

            $order->load('items');

            if ($order->items->isEmpty()) {
                throw new Exception('No items found for this order');
            }

            foreach ($order->items as $item) {

                $product = Product::find($item->product_id);

                if (!$product) {
                    continue;
                }

                $product->stock_on_hand = $product->stock_on_hand ?? 0;

                $product->increment('stock_on_hand', $item->quantity);
            }

            $order->update([
                'status' => 4
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Stock updated successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('warehouse.orders.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new WarehouseOrder();
        $order->supplier_id = $request->supplier_id;
        $order->status = 1;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Warehouse order created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function view($id)
    {
        $order = WarehouseOrder::with(['supplier', 'items'])->findOrFail($id);
        $ordereditems = WarehouseOrderItem::where('warehouse_order_id', $order->id)->count();

        $products = Product::where('status', 1)
            ->orderBy('description')
            ->get(['id', 'description', 'product_barcode1']);

        return view('warehouse_orders.view', compact('order', 'products', 'ordereditems'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getTotal($orderId)
    {
        $total = WarehouseOrderItem::where('warehouse_order_id', $orderId)
            ->sum('total');

        return response()->json([
            'total' => number_format($total, 2)
        ]);
    }
}
