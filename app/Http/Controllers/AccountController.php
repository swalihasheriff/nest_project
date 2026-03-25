<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest\StoreAccountRequest;
use App\Http\Requests\AccountRequest\UpdateAccountRequest;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $accounts = Account::latest();

            return DataTables::of($accounts)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                ->addColumn('action', function ($row) {
                    return '
        <a href="' . route('accounts.edit', $row->id) . '" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i>
        </a>

        <a href="' . route('accounts.sales', $row->id) . '" class="btn btn-sm btn-success">
            <i class="bi bi-cash"></i>
        </a>

        <a href="' . route('accounts.profile', $row->id) . '" class="btn btn-sm btn-info">
            <i class="bi bi-person"></i>
        </a>

        <button class="btn btn-sm btn-danger deleteAccountBtn" data-id="' . $row->id . '">
            <i class="bi bi-trash"></i>
        </button>
    ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $account = new Account();

        $account->company_name = $request->company_name;
        $account->address = $request->address;
        $account->abn = $request->abn;
        $account->email = $request->email;
        $account->contact_name = $request->contact_name;
        $account->contact_number = $request->contact_number;
        $account->remarks = $request->remarks;
        $account->accnt_ref_number = $request->accnt_ref_number;
        $account->username = $request->username;
        $account->password = $request->password;
        $account->price_group = $request->price_group;
        $account->status = $request->status ?? 1;

        $account->save();

        return response()->json([
            'status' => true,
            'success' => 'Account created successfully'
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
        $account = Account::findOrFail($id);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, string $id)
    {

        $account = Account::find($id);

        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ]);
        }

        $account->company_name = $request->company_name;
        $account->address = $request->address;
        $account->abn = $request->abn;
        $account->contact_name = $request->contact_name;
        $account->contact_number = $request->contact_number;
        $account->remarks = $request->remarks;
        $account->accnt_ref_number = $request->accnt_ref_number;
        $account->price_group = $request->price_group;
        $account->status = $request->status ?? 1;

        if ($request->filled('password')) {
            $account->password = Hash::make($request->password);
        }

        $account->save();

        return response()->json([
            'status' => true,
            'success' => 'Account updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ]);
        }

        $account->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}
