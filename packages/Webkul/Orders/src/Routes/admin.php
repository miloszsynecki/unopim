<?php

use Illuminate\Support\Facades\Route;
use Webkul\Orders\Http\Controllers\OrderController;

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function (): void {
    Route::controller(OrderController::class)->prefix('sales/orders')->group(function (): void {
        Route::get('', 'index')->name('admin.sales.orders.index');
        Route::get('{id}', 'view')->whereNumber('id')->name('admin.sales.orders.view');
    });
});
