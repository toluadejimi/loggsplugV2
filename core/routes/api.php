<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API / Webhook routes
|--------------------------------------------------------------------------
| e-fund is the webhook URL: send email, amount, order_id to credit user wallet.
*/

Route::any('e-check', [ApiController::class, 'e_check'])->name('api.e-check');
Route::any('e-fund', [ApiController::class, 'e_fund'])->name('api.e-fund');
Route::any('verify-username', [ApiController::class, 'verify_username'])->name('api.verify-username');
