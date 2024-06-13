<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
   Route::prefix('auth')->name('auth.')->group(function () {
       Route::post('register', [AuthController::class, 'register'])->name('register');
   });
});
