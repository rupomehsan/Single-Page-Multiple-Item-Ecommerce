<?php

use Modules\Management\OrderManagement\Order\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('orders')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:order-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:order-details|order-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:order-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:order-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:order-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:order-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:order-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:order-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:order-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:order-delete');
    });
});