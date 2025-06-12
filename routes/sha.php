<?php

use App\Http\Controllers\ShaClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/get_sha_token', [ShaClaimController::class, 'get_sha_token'])->name('get_sha_token');