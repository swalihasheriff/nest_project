<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard.index'),
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    public function index()
    {
        $supplierCount = Supplier::count();
        $productCount = Product::count();

        return view('dashboard.index', compact('supplierCount', 'productCount'));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
