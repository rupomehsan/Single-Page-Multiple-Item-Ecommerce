<?php

use Modules\Management\CMSManagement\Banner\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('banners')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:banner-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:banner-details|banner-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:banner-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:banner-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:banner-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:banner-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:banner-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:banner-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:banner-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:banner-delete');
    });
});