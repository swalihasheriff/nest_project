<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest\StoreProductRequest;
use App\Http\Requests\ProductRequest\UpdateProductRequest;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $products = Product::with('supplier')->latest();

            return DataTables::of($products)
                ->addIndexColumn()

                ->addColumn('supplier', function ($row): mixed {
                    return $row->supplier?->name ?? '-';
                })

                ->addColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-success status-badge" data-id="' . $row->id . '">Active</span>';
                    }
                    return '<span class="badge bg-secondary status-badge" data-id="' . $row->id . '">Inactive</span>';
                })

                ->addColumn('margin', function ($row) {
                    $cost = floatval($row->ctn_cost_price) ?: 0;
                    $sell = floatval($row->ctn_sell_price) ?: 0;
                    return $cost === 0 ? '0%' : round((($sell - $cost) / $cost) * 100, 2) . '%';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at
                        ? Carbon::parse($row->updated_at)->format('d-m-Y h:i A')
                        : '-';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        
                        <a href="' . route('products.view', $row->id) . '"
                           class="btn btn-sm btn-outline-secondary">
                           <i class="bi bi-eye"></i>
                        </a>
                        
                        <a href="' . route('products.edit', $row->id) . '"
                           class="btn btn-sm btn-outline-primary">
                           <i class="bi bi-pencil"></i>
                        </a>

                        <div class="form-check form-switch m-0">
                            <input class="form-check-input product-status-toggle"
                                type="checkbox"
                                data-id="' . $row->id . '"
                                ' . ($row->status ? 'checked' : '') . '>
                        </div>

                    </div>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 1)->get();

        return view('products.add', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product();

        // Basic info
        $product->description = $request->description;
        $product->supplier_id = $request->supplier_id;

        // Barcodes
        $product->ctn_barcode = $request->ctn_barcode;
        $product->upc = $request->upc;
        $product->product_barcode1 = $request->product_barcode1;
        $product->product_barcode2 = $request->product_barcode2;
        $product->product_barcode3 = $request->product_barcode3;

        // Codes
        $product->product_code = $request->product_code;
        $product->supplier_code = $request->supplier_code;

        // Prices
        $product->ctn_cost_price = $request->ctn_cost_price;
        $product->gst = $request->gst ?? 0;
        $product->ctn_sell_price = $request->ctn_sell_price;
        $product->sell_price2 = $request->sell_price2;
        $product->sell_price3 = $request->sell_price3;
        $product->sell_price4 = $request->sell_price4;
        $product->sell_price5 = $request->sell_price5;

        // Stock
        $product->stock_on_hand = $request->stock_on_hand;
        $product->minimum_threshold = $request->minimum_threshold;
        $product->shelf_capacity = $request->shelf_capacity;

        // Location & size
        $product->location = $request->location;
        $product->weight = $request->weight;
        $product->length = $request->length;
        $product->uom = $request->uom;

        // Flags
        $product->reorder = $request->has('reorder') ? 1 : 0;
        $product->status = 1;


        if ($request->hasFile('images')) {

            $paths = [];

            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('products', 'public');
            }

            // store as JSON in same table
            $product->images = $paths;
        }

        $product->save();

        return response()->json([
            'status' => true,
            'success' => 'Product added successfully'
        ]);
    }


    public function toggleStatus(Request $request, Product $product)
    {
        $product->status = $request->status;
        $product->save();
        return response()->json([
            'success' => true,
            'status' => $product->status
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function view($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::where('status', 1)->get();

        return view('products.view', compact('product', 'suppliers'));
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::where('status', 1)->get();
        return view('products.edit', ['product' => $product, 'suppliers' => $suppliers]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->description = $request->description;
        $product->supplier_id = $request->supplier_id;
        $product->ctn_barcode = $request->ctn_barcode;
        $product->upc = $request->upc;
        $product->product_barcode1 = $request->product_barcode1;
        $product->product_barcode2 = $request->product_barcode2;
        $product->product_barcode3 = $request->product_barcode3;

        $product->product_code = $request->product_code;
        $product->supplier_code = $request->supplier_code;

        $product->ctn_cost_price = $request->ctn_cost_price;
        $product->gst = $request->gst;
        $product->ctn_sell_price = $request->ctn_sell_price;
        $product->sell_price2 = $request->sell_price2;
        $product->sell_price3 = $request->sell_price3;
        $product->sell_price4 = $request->sell_price4;
        $product->sell_price5 = $request->sell_price5;

        $product->stock_on_hand = $request->stock_on_hand;
        $product->minimum_threshold = $request->minimum_threshold;
        $product->location = $request->location;

        $product->reorder = $request->reorder ? 1 : 0;

        $product->shelf_capacity = $request->shelf_capacity;
        $product->weight = $request->weight;
        $product->length = $request->length;
        $product->uom = $request->uom;
        $product->status = $request->status ?? 1;

        if ($request->hasFile('images')) {


            $existingImages = $product->images;

            if (is_string($existingImages)) {
                $existingImages = json_decode($existingImages, true) ?? [];
            }

            if (!is_array($existingImages)) {
                $existingImages = [];
            }

            foreach ($request->file('images') as $image) {
                $existingImages[] = $image->store('products', 'public');
            }

            $product->images = $existingImages;
        }

        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully'
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
