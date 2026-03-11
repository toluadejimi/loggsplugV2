<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
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
| Reseller API – public image URL (no auth) so reseller sites can show product images
| without hitting 403 from hotlink protection on /assets/...
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
