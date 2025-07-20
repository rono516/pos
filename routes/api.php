<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('pesapal/callback', [OrderController::class, 'handlePesapallCallback'])->name('pesapal.callback');
