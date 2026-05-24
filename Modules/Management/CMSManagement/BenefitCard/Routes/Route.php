<?php

use Modules\Management\CMSManagement\BenefitCard\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('benefit-cards')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:benefit-card-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:benefit-card-details|benefit-card-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:benefit-card-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:benefit-card-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:benefit-card-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:benefit-card-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:benefit-card-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:benefit-card-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:benefit-card-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:benefit-card-delete');
    });
});