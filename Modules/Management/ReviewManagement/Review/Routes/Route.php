<?php

use Modules\Management\ReviewManagement\Review\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('reviews')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:review-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:review-details|review-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:review-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:review-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:review-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:review-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:review-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:review-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:review-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:review-delete');
    });
});