<?php

use Modules\Management\PromoCodeManagement\PromoCode\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('promo-codes')->middleware('auth:api')->group(function () {
        Route::get('', [Controller::class,'index'])->middleware('permission:promo-code-view');
        Route::get('{slug}', [Controller::class,'show'])->middleware('permission:promo-code-details|promo-code-edit');
        Route::post('store', [Controller::class,'store'])->middleware('permission:promo-code-create');
        Route::post('update/{slug}', [Controller::class,'update'])->middleware('permission:promo-code-edit');
        Route::post('update-status', [Controller::class,'updateStatus'])->middleware('permission:promo-code-edit');
        Route::post('soft-delete', [Controller::class,'softDelete'])->middleware('permission:promo-code-delete');
        Route::post('destroy/{slug}', [Controller::class,'destroy'])->middleware('permission:promo-code-delete');
        Route::post('restore', [Controller::class,'restore'])->middleware('permission:promo-code-delete');
        Route::post('import', [Controller::class,'import'])->middleware('permission:promo-code-import');
        Route::post('bulk-action', [Controller::class, 'bulkAction'])->middleware('permission:promo-code-delete');
    });
});