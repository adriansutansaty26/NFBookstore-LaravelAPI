<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// URL: http://localhost:8000/api/books
Route::apiResource('/book', App\Http\Controllers\Api\BookController::class);

// URL: http://localhost:8000/api/payment
Route::apiResource('/payment', App\Http\Controllers\Api\PaymentController::class);

// URL: http://localhost:8000/api/shipping
Route::apiResource('/shipping', App\Http\Controllers\Api\ShippingController::class);

// URL: http://localhost:8000/api/order
Route::apiResource('/order', App\Http\Controllers\Api\OrderController::class);

// URL: http://localhost:8000/api/order_detail
Route::apiResource('/order_detail', App\Http\Controllers\Api\OrderDetailController::class);

// URL: http://localhost:8000/api/order_history
Route::apiResource('/order_history', App\Http\Controllers\Api\OrderHistoryController::class);

// URL: http://localhost:8000/api/cancel_order
Route::apiResource('/cancel_order', App\Http\Controllers\Api\CancelOrderController::class);
