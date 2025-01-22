<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\FileController;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::apiResource('files', FileController::class)->except('update');
});
