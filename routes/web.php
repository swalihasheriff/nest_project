<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\GoodsReceivingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManualInvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceHistoryController;
use App\Http\Controllers\StocktakeController;
use App\Http\Controllers\StocktakeItemController;
use App\Http\Controllers\WarehouseOrderController;
use App\Http\Controllers\WarehouseOrderItemController;
use App\Http\Middleware\CheckLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SupplierController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
})->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

Route::middleware(([CheckLogin::class]))->group(function () {


    Route::get('/dashboard', [LoginController::class, 'index'])
        ->middleware(['check.login'])
        ->name('dashboard.index');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware(['check.login'])
        ->name('logout');

    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])
            ->name('index');
        Route::post('/store', [SupplierController::class, 'store'])
            ->name('store');
        Route::post('/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])
            ->name('toggle-status');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [SupplierController::class, 'update'])->name('update');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [ProductController::class, 'update'])->name('update');
        Route::get('/{id}/view', [ProductController::class, 'view'])->name('view');
        Route::post('/{product}/toggle-status', [ProductController::class, 'toggleStatus']);
        Route::get('/search', [ProductController::class, 'search'])->name('search');
    });

    Route::prefix('warehouse-orders')->name('warehouse.orders.')->group(function () {
        Route::get('/', [WarehouseOrderController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseOrderController::class, 'create'])->name('create');
        Route::post('/store', [WarehouseOrderController::class, 'store'])->name('store');
        Route::get('/{id}/view', [WarehouseOrderController::class, 'view'])->name('view');
        Route::get('/{order}/total', [WarehouseOrderController::class, 'getTotal'])->name('total');
        Route::post('{order}/finalize', [WarehouseOrderController::class, 'finalize'])->name('finalize');
        Route::post('{order}/undo-finalize', [WarehouseOrderController::class, 'undoFinalize'])->name('undoFinalize');
        Route::post('{order}/cancel', [WarehouseOrderController::class, 'cancel'])->name('cancel');
        Route::post('{order}/deliver', [WarehouseOrderController::class, 'deliver'])->name('deliver');

        Route::prefix('{order}/items')->name('items.')->group(function () {
            Route::post('/auto-fill', [WarehouseOrderItemController::class, 'autoFill'])->name('autoFill');
            Route::get('/', [WarehouseOrderItemController::class, 'index'])->name('index');
            Route::post('/store', [WarehouseOrderItemController::class, 'store'])->name('store');
            Route::post('/{item}', [WarehouseOrderItemController::class, 'destroy'])->whereNumber('item')->name('destroy');
            Route::post('/{item}/update', [WarehouseOrderItemController::class, 'update'])->whereNumber('item')->name('update');
        });
    });

    Route::prefix('goods-receiving')->name('goods.receiving.')->group(function () {
        Route::get('/', [GoodsReceivingController::class, 'index'])->name('index');
        Route::post('/list-item', [GoodsReceivingController::class, 'listItem'])->name('list.item');
        Route::post('/store', [GoodsReceivingController::class, 'store'])->name('store');
        Route::get('{id}', [GoodsReceivingController::class, 'show'])->name('show');
        Route::post('{id}/update', [GoodsReceivingController::class, 'update'])->name('update');
        Route::post('{id}/finalize', [GoodsReceivingController::class, 'finalize'])->name('finalize');
        Route::post('{id}/undo-finalize', [GoodsReceivingController::class, 'undoFinalize'])->name('undoFinalize');

    });

    Route::prefix('stocktake')->name('stocktake.')->group(function () {

        Route::get('/', [StocktakeController::class, 'index'])->name('index');
        Route::post('/store', [StocktakeController::class, 'store'])->name('store');
        Route::get('{id}', [StocktakeController::class, 'show'])->name('show');
        Route::post('{id}/delete', [StocktakeController::class, 'destroy'])->name('destroy');
    });



    Route::prefix('stocktake-items')->name('stocktake-items.')->group(function () {
        Route::get('{id}/items', [StocktakeItemController::class, 'index'])->name('index');
        Route::post('{stocktake_id}/store', [StocktakeItemController::class, 'store'])->name('store');
        Route::post('delete/{id}', [StocktakeItemController::class, 'destroy'])->name('delete');
        Route::post('{stocktake}/update/{item}', [StocktakeItemController::class, 'update'])->name('update');
        Route::post('{stocktake_id}/fill-data', [StocktakeItemController::class, 'fillData'])->name('fillData');
        Route::post('{id}/finalize', [StocktakeItemController::class, 'finalize'])->name('finalize');
        // Route::post('{id}/undo-finalize', [StocktakeItemController::class, 'undoFinalize'])->name('undoFinalize');

    });



    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::post('{id}/update', [AccountController::class, 'update'])->name('update');
        Route::post('{id}/delete', [AccountController::class, 'destroy'])->name('destroy');
        Route::get('{id}/sales', [AccountController::class, 'sales'])->name('sales');
        Route::get('{id}/profile', [AccountController::class, 'profile'])->name('profile');
    });

    Route::prefix('manual-invoices')->name('manual-invoices.')->group(function () {

        Route::get('/', [ManualInvoiceController::class, 'index'])->name('index');
        Route::post('/store', [ManualInvoiceController::class, 'store'])->name('store');
        Route::post('/items/update', [ManualInvoiceController::class, 'updateItem'])->name('items.update');
        Route::post('/items/store', [ManualInvoiceController::class, 'storeItem'])->name('items.store');
        Route::post('{id}/update', [ManualInvoiceController::class, 'update'])->name('update');
        Route::get('{id}/view', [ManualInvoiceController::class, 'show'])->name('show');
        Route::get('{id}/items', [ManualInvoiceController::class, 'items'])->name('items');
        Route::post('/{id}/finalize', [ManualInvoiceController::class, 'finalize'])->name('finalize');
        Route::post('/{id}/undo-finalize', [ManualInvoiceController::class, 'undoFinalize'])->name('undoFinalize');
        Route::post('/items/delete', [ManualInvoiceController::class, 'deleteItem'])->name('items.delete');
    });

    Route::get('/product-price-history/{product_id}', [ProductPriceHistoryController::class, 'index'])->name('product-price-history');
});


