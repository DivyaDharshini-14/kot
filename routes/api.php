<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WaiterApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // 1. Mobile & Web Auth Endpoints
    Route::post('/auth/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout')->middleware('auth');
    Route::get('/auth/me', [AuthApiController::class, 'me'])->name('api.auth.me')->middleware('auth');

    // 2. Protected Waiter Workflow APIs (used by Flutter App & Web)
    Route::middleware(['auth', 'account.active'])->group(function () {
        // Floor & Table Selection
        Route::get('/waiter/tables', [WaiterApiController::class, 'tables'])
            ->name('api.waiter.tables')
            ->middleware('permission:table.view');

        // Category & Menu Item Selection
        Route::get('/waiter/menu', [WaiterApiController::class, 'menu'])
            ->name('api.waiter.menu')
            ->middleware('permission:menu-item.view');

        // Active Table Order & Status
        Route::get('/waiter/tables/{table}/active-order', [WaiterApiController::class, 'activeOrder'])
            ->name('api.waiter.active-order')
            ->middleware('permission:order.view');

        // Order Submission & Additional Items
        Route::post('/orders', [OrderApiController::class, 'store'])
            ->name('api.orders.store')
            ->middleware('permission:order.add');

        Route::get('/orders', [OrderApiController::class, 'index'])
            ->name('api.orders.index')
            ->middleware('permission:order.view');

        Route::get('/orders/{order}', [OrderApiController::class, 'show'])
            ->name('api.orders.show')
            ->middleware('permission:order.view');

        Route::post('/orders/{order}/items', [OrderApiController::class, 'addItems'])
            ->name('api.orders.add-items')
            ->middleware('permission:order.add');

        Route::get('/orders/{order}/kot', [OrderApiController::class, 'orderKot'])
            ->name('api.orders.kot')
            ->middleware('permission:order.view');

        // Kitchen Display & KOT Status APIs (for Kitchen Displays / Kitchen Staff)
        Route::get('/kitchens/{kitchen}/kot', [OrderApiController::class, 'kitchenKot'])
            ->name('api.kitchens.kot')
            ->middleware('permission:kot.view');

        Route::patch('/kot-tickets/{ticket}/status', [OrderApiController::class, 'updateTicketStatus'])
            ->name('api.kot-tickets.status')
            ->middleware('permission:kot.edit');

        Route::patch('/kot-items/{item}/status', [OrderApiController::class, 'updateItemStatus'])
            ->name('api.kot-items.status')
            ->middleware('permission:kot.edit');
    });
});
