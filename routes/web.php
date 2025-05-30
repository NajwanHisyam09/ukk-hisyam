<?php

use App\Exports\SalesExport;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SalesExportController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['authenticate'])->group(function () {
    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Product Route
    Route::resource('products', ProductController::class);

    // Sale Route
    // Route::get('/sales/{id}/invoice', [SaleController::class, 'showInvoice'])->name('sales.invoice');
    Route::get('/sales/{id}/invoice', [SaleController::class, 'showInvoice'])->name('sales.invoice');
    Route::resource('sales', SaleController::class);

    // Member Route
    Route::resource('members', MemberController::class);


    Route::middleware(['authenticate'])->group(function () {
        Route::get('/sales/export/excel', function () {
            return Excel::download(new SalesExport, 'sales.xlsx');
        })->name('sales.export');

        Route::middleware(['admin'])->group(function () {
            // User management, update stok, dll
            Route::resource('user', UserController::class);

            Route::get('/export', [SalesExportController::class, 'export']);

            Route::put('/products/{id}/update-stock', [ProductController::class, 'updateStock'])->name('products.updateStock');

            Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
            Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        });

        Route::middleware(['user'])->group(function () {
            Route::post('/confirm-sale', [SaleController::class, 'confirmationStore'])->name('sales.confirmationStore');
        });
    });

    // User Route
    Route::middleware(['user'])->group(function () {

        Route::post('/confirm-sale', [SaleController::class, 'confirmationStore'])->name('sales.confirmationStore');
        // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    });
});
