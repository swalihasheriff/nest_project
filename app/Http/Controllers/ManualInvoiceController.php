<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodsReceivingRequest\InvoiceRequest\StoreInvoiceItemRequest;
use App\Http\Requests\GoodsReceivingRequest\InvoiceRequest\StoreInvoiceRequest;
use App\Http\Requests\GoodsReceivingRequest\InvoiceRequest\UpdateInvoiceItemRequest;
use App\Http\Requests\GoodsReceivingRequest\InvoiceRequest\UpdateInvoiceRequest;
use App\Models\ManualInvoice;
use App\Models\ManualInvoiceItem;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManualInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::where('status', 1)->get();


        if ($request->ajax()) {

            $invoices = ManualInvoice::latest();

            return DataTables::of($invoices)
                ->addIndexColumn()

                ->addColumn('invoice_date', function ($row) {
                    return \Carbon\Carbon::parse($row->invoice_date)->format('d M Y');
                })

                ->addColumn('supplier', function ($row) {
                    $id = $row->supplier;
                    $supplier = Supplier::where('id', $id)->select('name')->first();

                    return $supplier->name ?? '-';
                })

                ->addColumn('received_on', function ($row) {
                    return \Carbon\Carbon::parse($row->received_on)->format('d M Y');
                })

                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Received</span>'
                        : '<span class="badge bg-danger">Finalised</span>';
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-primary editInvoiceBtn" 
                        data-invoice=\'' . json_encode($row) . '\'>
                        <i class="bi bi-pencil"></i>
                    </button>

                    <a href="' . route('manual-invoices.show', $row->id) . '" 
           class="btn btn-sm btn-info">
            <i class="bi bi-eye"></i>
        </a>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('goods_receivings.manual_invoices.index', compact('suppliers'));
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
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = new ManualInvoice();
        $invoice->supplier = $request->supplier;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->received_on = $request->received_on;
        $invoice->received_by = $request->received_by;

        $invoice->amount = $request->amount;
        $invoice->rounding = $request->rounding ?? 0;

        $invoice->status = $request->status ?? 1;

        $invoice->save();

        return response()->json([
            'status' => true,
            'success' => 'Invoice created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = ManualInvoice::with('suppliers')->find($id);
   

        if (!$invoice) {
            return redirect()->route('manual-invoices.index')
                ->with('error', 'Invoice not found');
        }
        $products = Product::select('id', 'description', 'product_barcode1')->where('supplier_id',$invoice->supplier)->get();

        return view('goods_receivings.manual_invoices.view', compact('invoice', 'products'));
    }

    public function items($id)
    {
        $items = ManualInvoiceItem::with(['product', 'invoice']) 
            ->where('manual_invoice_id', $id);

        return DataTables::of($items)
            ->addIndexColumn()

            ->addColumn('item_description', function ($row) {
                return optional($row->product)->description ?? '-';
            })

            ->addColumn('barcode', function ($row) {
                return $row->barcode ?? '-';
            })

            ->addColumn('price', function ($row) {
                return $row->item_price;
            })

            ->addColumn('quantity', function ($row) {
                return $row->quantity;
            })

            ->addColumn('action', function ($row) {

                $invoiceStatus = $row->invoice->status ?? 1;

                $buttons = '';

                if ($invoiceStatus == 1) {
                    $buttons .= '
                    <button class="btn btn-sm btn-primary editItemBtn"
                        data-id="' . $row->id . '"
                        data-price="' . $row->item_price . '"
                        data-qty="' . $row->quantity . '">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button class="btn btn-sm btn-danger deleteItemBtn"
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i>
                    </button>
                ';
                }

                $buttons .= '
                <button class="btn btn-sm btn-info priceHistoryBtn"
                    data-product-id="' . $row->product_id . '"
                    title="Price History">
                    <i class="bi bi-graph-up"></i>
                </button>
                
            ';

                return $buttons;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeItem(StoreInvoiceItemRequest $request)
    {
        $product = Product::findOrFail($request->product_id);


        ManualInvoiceItem::create([
            'manual_invoice_id' => $request->manual_invoice_id,
            'product_id' => $request->product_id,
            'barcode' => $product->product_barcode1,
            'item_price' => $product->ctn_cost_price,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Item added successfully'
        ]);
    }

    public function updateItem(UpdateInvoiceItemRequest $request)
    {
        $item = ManualInvoiceItem::findOrFail($request->id);
        if ($item->invoice->status == 2) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot update item. Invoice already finalized.'
            ]);
        }

        $item->update([
            'item_price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'status' => true,
            'success' => 'Item updated successfully'
        ]);
    }
    public function deleteItem(Request $request)
    {
        $item = ManualInvoiceItem::findOrFail($request->id);

        if ($item->invoice->status == 2) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete. Invoice finalized.'
            ]);
        }
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }



    public function finalize($id)
    {
        $invoice = ManualInvoice::with('items.product')->findOrFail($id);
        

        foreach ($invoice->items as $item) {

            $product = $item->product;
          

            if (!$product) {
                continue;
            }

            $product->increment('stock_on_hand', $item->quantity);
            $oldPrice = $product->ctn_cost_price;
            $newPrice = $item->item_price;

            $existing = ProductPriceHistory::where('product_id', $product->id)
                ->where('status', 1)
                ->first();

            if (!$existing || $existing->price != $newPrice) {
                ProductPriceHistory::where('product_id', $product->id)
                    ->where('status', 1)
                    ->update(['status' => 0]);


                ProductPriceHistory::create([
                    'product_id' => $product->id,
                    'price' => $newPrice,
                    'changed_from' => $existing->price ?? null,
                    'status' => 1
                ]);

                $product->update([
                    'ctn_cost_price' => $newPrice
                ]);
            }
        }


        $invoice->update([
            'status' => 2
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Invoice finalized successfully'
        ]);
    }


    public function undoFinalize($id)
    {
        $invoice = ManualInvoice::with('items.product')->findOrFail($id);

        if ($invoice->status == 1) {
            return response()->json(['status' => false, 'message' => 'Already received']);
        }

        foreach ($invoice->items as $item) {

            $product = Product::find($item->product_id);

            if ($product) {
                $product->stock_on_hand -= $item->quantity;
                $product->save();
            }
        }

        $invoice->status = 1;
        $invoice->save();

        return response()->json([
            'status' => true,
            'message' => 'Finalisation undone & stock reverted'
        ]);
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
    public function update(UpdateInvoiceRequest $request, string $id)
    {
        $invoice = ManualInvoice::find($id);

        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found'
            ]);
        }


        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->received_on = $request->received_on;
        $invoice->received_by = $request->received_by;
        $invoice->amount = $request->amount;
        $invoice->rounding = $request->rounding;
        $invoice->status = $request->status ?? 1;

        $invoice->save();

        return response()->json([
            'status' => true,
            'success' => 'Invoice updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
