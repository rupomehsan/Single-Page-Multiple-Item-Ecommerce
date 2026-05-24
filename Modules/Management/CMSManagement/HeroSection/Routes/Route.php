<?php

use Modules\Management\CMSManagement\HeroSection\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('hero-sections')->group(function () {
        Route::get('', [Controller::class, 'index']);
    });

    Route::prefix('hero-sections')->middleware('auth:api')->group(function () {
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:hero-section-details|hero-section-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:hero-section-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:hero-section-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:hero-section-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:hero-section-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:hero-section-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:hero-section-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:hero-section-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:hero-section-delete');
    });
});