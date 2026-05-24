<?php

use Modules\Management\ProductManagement\Product\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('', [Controller::class,'index']);
    });

    Route::prefix('products')->middleware('auth:api')->group(function () {
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:product-details|product-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:product-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:product-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:product-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:product-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:product-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:product-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:product-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:product-delete');
    });
});