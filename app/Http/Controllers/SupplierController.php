<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\SupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
public function index(Request $request)
{
    if ($request->ajax()) {

        return DataTables::of(
            Supplier::select([
                'id',
                'name',
                'phone',
                'email',
                'address',
                'contact_person',
                'status',
                'created_at'
            ])
        )
        ->addIndexColumn()

        ->editColumn('email', function ($supplier) {
            return $supplier->email ?? '-';
        })

        ->editColumn('address', function ($supplier) {
            return '<span class="text-truncate d-inline-block" style="max-width:250px;">'
                . e($supplier->address) .
            '</span>';
        })

        ->addColumn('status', function ($supplier) {
            $class = $supplier->status ? 'bg-success' : 'bg-secondary';
            $text  = $supplier->status ? 'Active' : 'Inactive';

            return '<span class="badge status-badge ' . $class . '" data-id="' . $supplier->id . '">' . $text . '</span>';
        })

        ->addColumn('action', function ($supplier) {
            return '
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-primary editSupplierBtn"
                        data-id="' . $supplier->id . '">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <div class="form-check form-switch m-0">
                        <input class="form-check-input supplier-status-toggle"
                            type="checkbox"
                            data-id="' . $supplier->id . '"
                            ' . ($supplier->status ? 'checked' : '') . '>
                    </div>
                </div>
            ';
        })

        ->rawColumns(['address', 'status', 'action'])
        ->make(true);
    }

    return view('suppliers.index');
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('suppliers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->contact_person = $request->contact_person;
        $supplier->status = 1;
        $supplier->save();

        return response()->json([
            'status' => true,
            'message' => 'Supplier added successfully'
        ]);
    }


    public function toggleStatus(Request $request, Supplier $supplier)
    {
        $supplier->status = $request->status;
        $supplier->save();

        return response()->json([
            'success' => true,
            'status' => $supplier->status
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
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Supplier updated successfully'
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
