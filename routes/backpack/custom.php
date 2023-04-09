<?php

use App\Http\Controllers\Admin\ReceiptsCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('receipts', 'ReceiptsCrudController');
    Route::any('create-receipt', [ReceiptsCrudController::class, 'createReceipt']);
    Route::any('create-receipt-action', [ReceiptsCrudController::class, 'createReceiptAction']);
    Route::any('get-main-info', [ReceiptsCrudController::class, 'getMainInfo']);
    Route::crud('receipts-price', 'ReceiptsPriceCrudController');
    Route::crud('bar-items', 'BarItemsCrudController');
    Route::crud('bar-items-sold', 'BarItemsSoldCrudController');
    Route::crud('game-users', 'GameUsersCrudController');
}); // this should be the absolute last line of this file
