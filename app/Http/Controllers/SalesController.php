<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest\AddSalesItemRequest;
use App\Http\Requests\SalesRequest\AddSalesRequest;
use App\Http\Requests\SalesRequest\UpdateSalesRequest;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $sale = Sales::with('account')->latest();

            if ($request->status !== null && $request->status !== '') {
                $sale->where('status', $request->status);
            }

            return DataTables::of($sale)

                ->addIndexColumn()

                ->addColumn('invoice_no', function ($row) {
                    return 10000 + $row->id;
                })

                ->addColumn('account', function ($row) {
                    return $row->account->company_name ?? '-';
                })

                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Finalized</span>'
                        : '<span class="badge bg-warning">Initialized</span>';
                })

                ->editColumn('type', function ($row) {
                    return $row->type == 1 ? 'Delivery' : 'Pickup';
                })

                ->editColumn('delivery_date', function ($row) {
                    return $row->delivery_date
                        ? date('d-m-Y', strtotime($row->delivery_date))
                        : '-';
                })

                ->editColumn('total_amount', function ($row) {
                    return '₹ ' . number_format($row->total_amount, 2);
                })

                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })

                ->editColumn('finalized_at', function ($row) {
                    return $row->finalized_at
                        ? date('d-m-Y', strtotime($row->finalized_at))
                        : '-';
                })

                ->addColumn('action', function ($row) {

                    $editBtn = $row->status == 0
                        ? '<button class="btn btn-sm btn-primary editSaleBtn" data-sale=\'' . json_encode($row) . '\'> <i class="bi bi-pencil"></i> </button>'
                        : '';
                    return '
                    <a href="' . route('sales.show', $row->id) . '" 
   class="btn btn-sm btn-info">
    <i class="bi bi-eye"></i>
</a>
                    ' . $editBtn . '
                    <button class="btn btn-sm btn-danger deleteSaleBtn" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $accounts = Account::where('status', 1)->get();
        return view('sales.index', compact('accounts'));
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
    public function store(AddSalesRequest $request)
    {
        $sale = new Sales();

        $sale->account_id = $request->account_id;
        $sale->type = $request->type;
        $sale->reference = $request->reference;
        $sale->delivery_date = $request->delivery_date;
        $sale->status = 0;
        $sale->total_amount = 0;

        $sale->save();

        return response()->json([
            'status' => true,
            'message' => 'Sale created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sale = Sales::with('account', 'items')->findOrFail($id);

        $hasItems = $sale->items->count() > 0;
        $products = Product::where('status', 1)->get();

        return view('sales.view', compact('sale', 'hasItems', 'products'));
    }

    public function items($id)
    {
        if (request()->ajax()) {

            $itemsQuery = SalesItem::with('product')
                ->where('sale_id', $id);

            $items = $itemsQuery->get();
            $total_excl = 0;
            $total_gst = 0;

            foreach ($items as $item) {

                $price = $item->product->ctn_sell_price ?? 0;
                $qty = $item->quantity ?? 0;
                $discount = $item->discount ?? 0;
                $gstPercent = $item->product->gst ?? 0;

                $lineTotal = ($price * $qty) - $discount;
                $lineGst = $lineTotal * ($gstPercent / 100);

                $total_excl += $lineTotal;
                $total_gst += $lineGst;
            }

            $total = $total_excl + $total_gst;
            $grand_total = round($total);

            return DataTables::of($itemsQuery)

                ->addIndexColumn()

                ->addColumn('description', function ($row) {
                    return $row->product->description ?? '-';
                })

                ->addColumn('barcode', function ($row) {
                    return $row->product->product_barcode1 ?? '-';
                })

                ->addColumn('soh', function ($row) {
                    return $row->product->stock_on_hand ?? 0;
                })

                ->addColumn('price', function ($row) {
                    return number_format($row->product->ctn_sell_price ?? 0, 2);
                })

                ->addColumn('quantity', function ($row) {
                    return (int) $row->quantity;
                })

                ->addColumn('discount', function ($row) {
                    return number_format($row->discount ?? 0, 2);
                })

                ->addColumn('product_id', function ($row) {
                    return $row->product_id;
                })

                ->addColumn('total', function ($row) {

                    $price = $row->product->ctn_sell_price ?? 0;
                    $qty = $row->quantity ?? 0;
                    $discount = $row->discount ?? 0;
                    $gstPercent = $row->product->gst ?? 0;

                    $lineTotal = ($price * $qty) - $discount;
                    $lineGst = $lineTotal * ($gstPercent / 100);

                    return number_format($lineTotal + $lineGst, 2);
                })

                ->addColumn('action', function ($row) {

                    if ($row->sale->status == 1) {
                        return '';
                    }

                    return '
              <button class="btn btn-sm btn-primary editItemBtn"
            data-id="' . $row->id . '"
            data-qty="' . $row->quantity . '">
            <i class="bi bi-pencil"></i>
        </button>               

        <button class="btn btn-sm btn-danger deleteItemBtn"
            data-id="' . $row->id . '">
            <i class="bi bi-trash"></i>
        </button>
    ';
                })

                ->with([
                    'total_excl' => number_format($total_excl, 2),
                    'gst' => number_format($total_gst, 2),
                    'total' => number_format($total, 2),
                    'grand_total' => number_format($grand_total, 2),
                ])

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function updateSaleTotal($saleId)
    {
        $items = SalesItem::with('product')
            ->where('sale_id', $saleId)
            ->get();

        $total = 0;

        foreach ($items as $item) {

            $price = $item->product->ctn_sell_price ?? 0;
            $qty = $item->quantity ?? 0;
            $discount = $item->discount ?? 0;
            $gst = $item->product->gst ?? 0;

            $line = ($price * $qty) - $discount;
            $gstAmount = $line * ($gst / 100);

            $total += ($line + $gstAmount);
        }

        $sale = Sales::find($saleId);
        if ($sale) {
            $sale->total_amount = $total;
            $sale->save();
        }
    }

    public function storeItem(AddSalesItemRequest $request)
    {
        $sale = Sales::find($request->sale_id);

        if ($sale->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Sale already finalized'
            ]);
        }

        $product = Product::findOrFail($request->product_id);
        $price = $product->ctn_sell_price;

        $lineTotal = ($price * $request->quantity) - ($request->discount ?? 0);

        SalesItem::create([
            'sale_id' => $request->sale_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'discount' => $request->discount ?? 0,
            'total_amount' => $lineTotal,
        ]);

        $this->updateSaleTotal($request->sale_id);

        return response()->json([
            'status' => true,
            'message' => 'Item added successfully'
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $item = SalesItem::with('product')->find($id);

        if ($item->sale->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete finalized sale item'
            ]);
        }

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }

        $qty = $request->quantity;
        $price = $item->product->ctn_sell_price ?? 0;
        $discount = $item->discount ?? 0;

        $total = ($price * $qty) - $discount;

        $item->quantity = $qty;
        $item->total_amount = $total;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Quantity updated successfully'
        ]);
    }

    public function finalize($id)
    {
        $sale = Sales::find($id);

        if (!$sale) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found'
            ]);
        }

        if ($sale->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Already finalized'
            ]);
        }


        $sale->status = 1;
        $sale->finalized_at = now();
        $sale->save();

        return response()->json([
            'status' => true,
            'message' => 'Sale finalized successfully'
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
    public function update(UpdateSalesRequest $request, $id)
    {
        $sale = Sales::findOrFail($id);

        $sale->reference = $request->reference;
        $sale->delivery_date = $request->delivery_date;
        $sale->delivery_address = $request->delivery_address;
        $sale->round = $request->round ?? 0;
        $sale->payment_mode = $request->payment_mode;

        $sale->save();

        return response()->json([
            'status' => true,
            'message' => 'Sale updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sales::find($id);

        if (!$sale) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found'
            ]);
        }



        $sale->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sale deleted successfully'
        ]);
    }

    public function deleteItem($id)
    {
        $item = SalesItem::find($id);

        if ($item->sale->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot edit finalized sale item'
            ]);
        }

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }
}
