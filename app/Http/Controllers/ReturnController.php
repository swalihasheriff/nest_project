<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnRequest\StoreReturnRequest;
use App\Models\GoodsReceiving;
use App\Models\ManualInvoice;
use App\Models\ManualInvoiceItem;
use App\Models\Product;
use App\Models\ReturnedProduct;
use App\Models\ReturnItem;
use App\Models\WarehouseOrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReturnController extends Controller
{

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $returns = ReturnedProduct::latest();

            return DataTables::of($returns)
                ->addIndexColumn()

                ->addColumn('invoice_number', function ($row) {

                    return $row->return->manualInvoice->invoice_number
                        ?? $row->return->goodsReceiving->invoice_number
                        ?? '-';
                })

                ->addColumn('type', function ($row) {
                    return $row->goods_receiving_id
                        ? 'Goods Receiving'
                        : 'Manual Invoice';
                })

                ->addColumn('date', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d M Y')
                        : '-';
                })

                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Finalized</span>'
                        : '<span class="badge bg-warning">Draft</span>';
                })

                ->addColumn('action', function ($row) {

                    $url = route('returns.index', [
                        'goods_receiving_id' => $row->goods_receiving_id,
                        'manual_invoice_id' => $row->manual_invoice_id
                    ]);

                    return '
                    <a href="' . $url . '" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                    </a>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('returns.list');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $goodsReceivingId = $request->goods_receiving_id;
        $manualInvoiceId = $request->manual_invoice_id;

        $type = null;
        $data = null;

        if ($goodsReceivingId) {
            $type = 'goods_receiving';
            $data = GoodsReceiving::with(['supplier', 'warehouseOrder'])
                ->findOrFail($goodsReceivingId);
        }

        if ($manualInvoiceId) {
            $type = 'manual_invoice';
            $data = ManualInvoice::findOrFail($manualInvoiceId);
        }

        $query = ReturnedProduct::query();

        if ($goodsReceivingId) {
            $query->where('goods_receiving_id', $goodsReceivingId);
        }

        if ($manualInvoiceId) {
            $query->where('manual_invoice_id', $manualInvoiceId);
        }

        $return = $query->first();

        $isFinalized = $return && $return->status == 1;

        $returnItems = collect();

        if ($return) {
            $returnItems = ReturnItem::where('return_id', $return->id)
                ->get()
                ->keyBy('item_id');
        }

        if ($request->ajax() && $request->type == 'items') {

            if ($goodsReceivingId) {

                $receiving = GoodsReceiving::findOrFail($goodsReceivingId);

                $items = WarehouseOrderItem::with('product')
                    ->where('warehouse_order_id', $receiving->warehouse_order_id);

                return DataTables::of($items)
                    ->addIndexColumn()
                    ->addColumn('description', fn($row) => $row->product->description ?? '-')
                    ->addColumn('barcode', fn($row) => $row->product->product_barcode1 ?? '-')
                    ->addColumn('ordered_qty', fn($row) => $row->quantity)

                    ->addColumn('return_qty', function ($row) use ($returnItems, $isFinalized) {

                        $existing = $returnItems[$row->id] ?? null;
                        $value = $existing?->return_qty ?? 0;
                        $disabled = $isFinalized ? 'disabled' : '';

                        return '
                    <input type="hidden" name="items[' . $row->id . '][item_id]" value="' . $row->id . '">

                    <input type="number"
                        name="items[' . $row->id . '][return_qty]"
                        class="form-control return-qty"
                        min="0"
                        max="' . $row->quantity . '"
                        value="' . $value . '"
                        ' . $disabled . '
                    >';
                    })
                    ->rawColumns(['return_qty'])
                    ->make(true);
            }

            if ($manualInvoiceId) {

                $items = ManualInvoiceItem::with('product')
                    ->where('manual_invoice_id', $manualInvoiceId);

                return DataTables::of($items)
                    ->addIndexColumn()
                    ->addColumn('description', fn($row) => $row->product->description ?? '-')
                    ->addColumn('barcode', fn($row) => $row->barcode ?? '-')
                    ->addColumn('ordered_qty', fn($row) => $row->quantity)

                    ->addColumn('return_qty', function ($row) use ($returnItems, $isFinalized) {

                        $existing = $returnItems[$row->id] ?? null;
                        $value = $existing?->return_qty ?? 0;
                        $disabled = $isFinalized ? 'disabled' : '';

                        return '
                    <input type="hidden" name="items[' . $row->id . '][item_id]" value="' . $row->id . '">

                    <input type="number"
                        name="items[' . $row->id . '][return_qty]"
                        class="form-control return-qty"
                        min="0"
                        max="' . $row->quantity . '"
                        value="' . $value . '"
                        ' . $disabled . '
                    >';
                    })
                    ->rawColumns(['return_qty'])
                    ->make(true);
            }
        }

        return view('returns.index', compact(
            'type',
            'data',
            'goodsReceivingId',
            'manualInvoiceId',
            'isFinalized'
        ));
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
    public function store(StoreReturnRequest $request)
    {
        DB::beginTransaction();

        try {

            $hasValidReturn = false;

            $return = ReturnedProduct::updateOrCreate(
                [
                    'goods_receiving_id' => $request->goods_receiving_id,
                    'manual_invoice_id' => $request->manual_invoice_id
                ],
                [
                    'invoice_number' => $request->invoice_number,
                    'status' => 1
                ]
            );

            foreach ($request->items as $item) {

                $itemId = $item['item_id'];
                $qty = (int) $item['return_qty'];

                $existing = ReturnItem::where([
                    'return_id' => $return->id,
                    'item_id' => $itemId
                ])->first();

                $oldQty = $existing?->return_qty ?? 0;

                if ($qty <= 0) {

                    if ($existing) {

                        $productId =
                            optional($existing->warehouseOrderItem)->product_id
                            ?? optional($existing->manualInvoiceItem)->product_id;

                        $product = Product::find($productId);

                        if ($product) {
                            $product->stock_on_hand += $oldQty;
                            $product->save();
                        }

                        $existing->delete();
                    }

                    continue;
                }

                $hasValidReturn = true;

                ReturnItem::updateOrCreate(
                    [
                        'return_id' => $return->id,
                        'item_id' => $itemId
                    ],
                    [
                        'return_qty' => $qty
                    ]
                );

                // 🔹 stock adjustment (DIFF logic)
                $diff = $qty - $oldQty;

                if ($diff != 0) {

                    $orderItem = WarehouseOrderItem::find($itemId);
                    $invoiceItem = ManualInvoiceItem::find($itemId);

                    $productId =
                        optional($orderItem)->product_id
                        ?? optional($invoiceItem)->product_id;

                    $product = Product::find($productId);

                    if ($product) {
                        $product->stock_on_hand = max(0, $product->stock_on_hand - $diff);
                        $product->save();
                    }
                }
            }

            if (!$hasValidReturn) {

                ReturnItem::where('return_id', $return->id)->delete();
                $return->delete();

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Enter at least one return quantity'
                ], 422);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Return finalized successfully'
            ]);

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function undoFinalize($id)
    {
        DB::beginTransaction();

        try {

            $return = ReturnedProduct::where('goods_receiving_id', $id)
                ->orWhere('manual_invoice_id', $id)
                ->first();

            if (!$return || $return->status != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nothing to undo'
                ], 422);
            }

            $items = ReturnItem::where('return_id', $return->id)->get();

            foreach ($items as $item) {

                $productId =
                    optional($item->warehouseOrderItem)->product_id
                    ?? optional($item->manualInvoiceItem)->product_id;

                $product = Product::find($productId);

                if ($product) {
                    $product->stock_on_hand += $item->return_qty;
                    $product->save();
                }
            }

            ReturnItem::where('return_id', $return->id)->delete();

            $return->status = 0;
            $return->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Undo successful'
            ]);

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Undo failed'
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
}
