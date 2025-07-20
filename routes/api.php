<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("pesapal/ipn", function (){

});
Route::post('pesapal/callback', [OrderController::class, 'handlePesapallCallback'])->name('pesapal.callback');
