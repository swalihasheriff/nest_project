<?php

namespace App\Http\Controllers;

use App\Http\Requests\StocktakeRequest\StoreStocktakeRequest;
use App\Models\Product;
use App\Models\StocktakeItem;
use Illuminate\Http\Request;
use App\Models\Stocktake;
use Yajra\DataTables\Facades\DataTables;

class StocktakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {

        if ($request->ajax()) {

            $stocktakes = Stocktake::select('id', 'worksheet_name', 'type', 'status', 'created_at')
                ->latest();

            return DataTables::of($stocktakes)

                ->addIndexColumn()

                ->editColumn('type', function ($row) {
                    return $row->type == 1 ? 'Full Stocktake' : 'Selective Stocktake';
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 0) {
                        return '<span class="badge bg-warning">Initiated</span>';
                    }

                    return '<span class="badge bg-success">Finalised</span>';
                })

                ->addColumn('action', function ($row) {

                    $url = route('stocktake.show', $row->id);

                    return '
                    <a href="' . $url . '" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>

                    <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">
                      <i class="bi bi-trash"></i>
                    </button>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('stocktake.index');
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
    public function store(StoreStocktakeRequest $request)
    {
        $stocktake = new Stocktake();
        $stocktake->worksheet_name = $request->worksheet_name;
        $stocktake->type = $request->type;
        $stocktake->status = 0;
        $stocktake->save();

        return response()->json([
            'status' => true,
            'message' => 'Stocktake worksheet created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */


    public function show($id)
    {
        $stocktake = Stocktake::findOrFail($id);

        $products = Product::select(
            'id',
            'description',
            'product_barcode1',
            'product_barcode2',
            'product_barcode3',
            'upc',
            'ctn_barcode'
        )->limit(500)->get();
        return view('stocktake.view', compact('stocktake', 'products'));
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
        $stocktake = Stocktake::findOrFail($id);
        $stocktake->delete();

        return response()->json([
            'status' => true,
            'message' => 'Stocktake deleted successfully'
        ]);
    }
}
