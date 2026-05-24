<?php

use Modules\Management\FAQManagement\FAQ\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('f-a-qs')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:f-a-q-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:f-a-q-details|f-a-q-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:f-a-q-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:f-a-q-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:f-a-q-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:f-a-q-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:f-a-q-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:f-a-q-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:f-a-q-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:f-a-q-delete');
    });
});