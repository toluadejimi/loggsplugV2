<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AppApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ResellerApiController;

/*
|--------------------------------------------------------------------------
| API / Webhook routes
|--------------------------------------------------------------------------
| e-fund is the webhook URL: send email, amount, order_id to credit user wallet.
*/

Route::any('e-check', [ApiController::class, 'e_check'])->name('api.e-check');
Route::any('e-fund', [ApiController::class, 'e_fund'])->name('api.e-fund');
Route::any('verify-username', [ApiController::class, 'verify_username'])->name('api.verify-username');

/*
|--------------------------------------------------------------------------
| App API (React frontend) – public endpoints
|--------------------------------------------------------------------------
*/
Route::get('categories', [AppApiController::class, 'categories']);
Route::get('products', [AppApiController::class, 'products']);
Route::get('products/{id}', [AppApiController::class, 'productDetails'])->where('id', '[0-9]+');
Route::get('gateway-currencies', [AppApiController::class, 'gatewayCurrencies']);

Route::post('login', [AuthApiController::class, 'login']);
Route::post('register', [AuthApiController::class, 'register']);

/*
|--------------------------------------------------------------------------
| App API (React frontend) – auth required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('user', [AppApiController::class, 'user']);
    Route::get('dashboard', [AppApiController::class, 'dashboard']);
    Route::get('orders', [AppApiController::class, 'orders']);
    Route::get('orders/{id}', [AppApiController::class, 'orderDetails'])->where('id', '[0-9]+');
    Route::get('category-products/{id}', [AppApiController::class, 'categoryProducts'])->where('id', '[0-9]+');
});

/*
|--------------------------------------------------------------------------
| Reseller API – public image URL (no auth)
|--------------------------------------------------------------------------
*/
Route::get('reseller/product-image/{id}', [ResellerApiController::class, 'productImage'])->name('api.reseller.product-image');

/*
|--------------------------------------------------------------------------
| Reseller API (authenticate with X-Api-Key or api_key in body)
|--------------------------------------------------------------------------
*/
Route::prefix('reseller')->middleware('reseller.api')->group(function () {
    Route::get('products', [ResellerApiController::class, 'products'])->name('api.reseller.products');
    Route::post('order', [ResellerApiController::class, 'placeOrder'])->name('api.reseller.order');
    Route::post('report-order', [ResellerApiController::class, 'reportOrder'])->name('api.reseller.report-order');
    Route::get('me', [ResellerApiController::class, 'me'])->name('api.reseller.me');
});
