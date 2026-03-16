<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodsReceivingRequest\AddReceivingItemRequest;
use App\Http\Requests\GoodsReceivingRequest\StoreGoodsReceivingRequest;
use App\Models\GoodsReceiving;
use App\Models\Product;
use App\Models\WarehouseOrder;
use App\Models\WarehouseOrderItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GoodsReceivingController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $orders = GoodsReceiving::with(['supplier', 'warehouseOrder'])
                ->orderByDesc('created_at');

            return DataTables::of($orders)
                ->addIndexColumn()

                ->addColumn('supplier', function ($row) {
                    return $row->supplier?->name ?? '-';
                })

                ->addColumn('received_on', function ($row) {
                    return $row->received_on
                        ? \Carbon\Carbon::parse($row->received_on)->format('d-m-Y')
                        : '-';
                })

                ->addColumn('received_by', function ($row) {
                    return $row->received_by ?? '-';
                })

                ->addColumn('status', function ($row) {

                    if ($row->status == 0) {
                        return '<span class="badge bg-warning">Delivered</span>';
                    }

                    return '<span class="badge bg-success">Received</span>';
                })

                ->addColumn('action', function ($row) {

                    $editButton = '';

                    if ($row->status == 1) {
                        $editButton = '
        <button class="btn btn-sm btn-primary edit-receiving"
            data-id="' . $row->id . '">
            <i class="bi bi-pencil"></i>
        </button>';
                    }

                    return '
    <div class="d-flex justify-content-center gap-1">

        ' . $editButton . '

        <a href="' . route('goods.receiving.show', $row->id) . '"
           class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye"></i>
        </a>

    </div>';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('goods_receivings.index');
    }

    public function create()
    {
        //
    }

    public function listItem(AddReceivingItemRequest $request)
    {
        $product = Product::find($request->product_id);

        return response()->json([
            'status' => true,
            'message' => 'Item added successfully',
            'data' => [
                'product_id' => $product->id,
                'description' => $product->description,
                'barcode' => $product->product_barcode1,
                'price' => $product->ctn_cost_price,
                'quantity' => $request->quantity
            ]
        ]);
    }

    public function store(StoreGoodsReceivingRequest $request)
    {

        $receiving = GoodsReceiving::create([
            'warehouse_order_id' => $request->order_id,
            'supplier_id' => $request->supplier_id,
            'received_by' => $request->received_by,
            'received_on' => $request->received_on,
            'status' => 1
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Goods received successfully'
        ]);
    }

    public function show($id)
    {
        $receiving = GoodsReceiving::with(['supplier', 'warehouseOrder'])
            ->findOrFail($id);

        $orderItems = WarehouseOrderItem::with('product')
            ->where('warehouse_order_id', $receiving->warehouse_order_id)
            ->get();

        $products = Product::select('id', 'description', 'product_barcode1')->get();

        return view('goods_receivings.view', compact('receiving', 'orderItems', 'products'));

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $receiving = GoodsReceiving::findOrFail($id);
        if ($receiving->status != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Update allowed only after receiving is finalized'
            ]);
        }

        $receiving->update([
            'received_by' => $request->received_by,
            'received_on' => $request->received_on,

        ]);

        return response()->json([
            'status' => true,
            'success' => 'Goods receiving updated successfully'
        ]);
    }

    public function finalize(Request $request, $id)
    {

        $receiving = GoodsReceiving::findOrFail($id);




        if ($receiving->finalized_at) {
            return response()->json([
                'status' => false,
                'message' => 'Already finalized'
            ]);
        }

        $productIds = $request->product_id;
        $suppliedQty = $request->supplied_qty;




        foreach ($productIds as $key => $productId) {
            $updateSuppliedQty = WarehouseOrderItem::where('warehouse_order_id', $receiving->warehouse_order_id)->first();
            $updateSuppliedQty->supplied_quantity = $suppliedQty[$key];
            $updateSuppliedQty->save();

            $product = Product::find($productId);

            if ($product) {
                $product->stock_on_hand += $suppliedQty[$key];
                $product->save();
            }

        }

        $receiving->status = 1;
        $receiving->finalized_at = now();
        $receiving->save();

        return response()->json([
            'status' => true,
            'message' => 'Receiving finalized successfully'
        ]);
    }


    public function undoFinalize(Request $request, $id)
    {
        $receiving = GoodsReceiving::findOrFail($id);

        $productIds = $request->product_id;
        $suppliedQty = $request->supplied_qty;


        foreach ($productIds as $key => $productId) {

            $updateSuppliedQty = WarehouseOrderItem::where('warehouse_order_id', $receiving->warehouse_order_id)->first();
            $updateSuppliedQty->supplied_quantity = 0;
            $updateSuppliedQty->save();

            $product = Product::find($productId);

            if ($product) {
                $product->stock_on_hand -= $suppliedQty[$key];
                $product->save();
            }
        }

        $receiving->status = 0;
        $receiving->received_by = null;
        $receiving->received_on = null;
        $receiving->finalized_at = null;
        $receiving->save();

        return response()->json([
            'status' => true,
            'message' => 'Finalize undone'
        ]);
    }

    public function destroy($id)
    {
        //
    }
}