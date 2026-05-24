<?php

use Modules\Management\DeliveryManagement\DeliveryArea\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('delivery-areas')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:delivery-area-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:delivery-area-details|delivery-area-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:delivery-area-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:delivery-area-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:delivery-area-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:delivery-area-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:delivery-area-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:delivery-area-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:delivery-area-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:delivery-area-delete');
    });
});