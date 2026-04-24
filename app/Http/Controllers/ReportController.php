<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reports.index');
    }

    public function stock(Request $request)
    {
        $suppliers = Supplier::where('status', 1)->get();

        $products = Product::with('supplier')
            ->when($request->supplier_id, function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            })
            ->get();

        return view('reports.stock', compact('suppliers', 'products'));
    }

    public function exportStock(Request $request)
    {
        $supplierId = $request->supplier_id;

        $products = Product::when($supplierId, function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->get();

        $filename = "stock_report.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($products) {

            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Product Barcode',
                'Name',
                'Stock On Hand'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->product_barcode1,
                    $product->description,
                    $product->stock_on_hand
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Pickup Report
    public function pickup()
    {
        $fromDate = request('from_date');
        $toDate = request('to_date');
        $pickupItems = collect();

        if ($fromDate && $toDate) {
            $pickupItems = SalesItem::whereHas('sale', function ($q) use ($fromDate, $toDate) {
                $q->where('type', 0) // 0 = Pickup
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
            })
                ->with(['sale.account', 'product'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('reports.pickup', compact('pickupItems'));
    }

    public function exportPickup(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $pickupItems = SalesItem ::whereHas('sale', function ($q) use ($fromDate, $toDate) {
            $q->where('type', 0)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate);
        })
            ->with(['sale.account', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "pickup_report_" . $fromDate . "_to_" . $toDate . ".csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($pickupItems) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Account Name',
                'Invoice Number',
                'Work Order Number',
                'Pickup Date',
                'Product',
                'Quantity',
                'Payment Mode'
            ]);

            foreach ($pickupItems as $item) {
                fputcsv($file, [
                    $item->sale->account->company_name ?? '',
                    (10000 + $item->sale->id),
                    $item->sale->reference ?? '',
                    $item->sale->created_at->format('d-m-Y H:i'),
                    $item->product->description ?? '',
                    $item->quantity,
                    $item->sale->payment_mode ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Sales Report
    public function sales()
    {
        $fromDate = request('from_date');
        $toDate = request('to_date');
        $salesItems = collect();

        if ($fromDate && $toDate) {
            $salesItems = SalesItem::whereHas('sale', function ($q) use ($fromDate, $toDate) {
                $q->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
            })
                ->with(['sale', 'product'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('reports.sales', compact('salesItems'));
    }

    public function exportSales(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $salesItems = SalesItem::whereHas('sale', function ($q) use ($fromDate, $toDate) {
            $q->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate);
        })
            ->with(['sale', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "sales_report_" . $fromDate . "_to_" . $toDate . ".csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($salesItems) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Items',
                'Quantity Sold',
                'Price',
                'Discount',
                'Total'
            ]);

            foreach ($salesItems as $item) {
                fputcsv($file, [
                    $item->product->description ?? '',
                    $item->quantity,
                    $item->product->ctn_sell_price,
                    $item->discount,
                    $item->total_amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Sales Sum
    public function salesSum()
    {
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $totalSales = 0;
        $totalTransactions = 0;

        if ($fromDate && $toDate) {
            $totalSales = Sales::whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('total_amount');

            $totalTransactions = Sales::whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->count();
        }

        return view('reports.sales_sum', compact('totalSales', 'totalTransactions'));
    }

}

