<?php

use Modules\Management\CMSManagement\HowItWorksStep\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('how-it-works-steps')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:how-it-works-step-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:how-it-works-step-details|how-it-works-step-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:how-it-works-step-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:how-it-works-step-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:how-it-works-step-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:how-it-works-step-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:how-it-works-step-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:how-it-works-step-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:how-it-works-step-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:how-it-works-step-delete');
    });
});