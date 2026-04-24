<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Supplier;
use App\Models\WarehouseOrder;
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
        $orderCount = WarehouseOrder::count();

        $pickupsToday = Sales::where('type', 0) // 0 = Pickup
            ->whereDate('created_at', today())
            ->count();

        $salesYesterday = Sales::whereDate('created_at', today()->subDay())
            ->sum('total_amount');

        $salesToday = Sales::whereDate('created_at', today())
            ->sum('total_amount');

        $pendingDeliveries = Sales::where('type', 1)  // 1 = Delivery
            ->where('status', 0) // 0 = Not finalized
            ->count();

        $salesChartData = [];
        $salesChartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesChartLabels[] = $date->format('D'); // Mon, Tue, etc

            $amount = Sales::whereDate('created_at', $date)
                ->sum('total_amount');

            $salesChartData[] = $amount;
        }

        $totalOrders = Sales::count();
        $totalDelivered = Sales::where('status', 1)->count(); 
        $totalPending = Sales::where('status', 0)->count();  

        return view('dashboard.index', compact(
            'supplierCount',
            'productCount',
            'orderCount',
            'pickupsToday',
            'salesYesterday',
            'salesToday',
            'pendingDeliveries',
            'salesChartLabels',
            'salesChartData',
            'totalOrders',
            'totalDelivered',
            'totalPending'
        ));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }


}
