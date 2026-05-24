<?php

use Modules\Management\CustomerManagement\Customer\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('customers')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:customer-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:customer-details|customer-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:customer-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:customer-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:customer-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:customer-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:customer-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:customer-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:customer-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:customer-delete');
    });
});