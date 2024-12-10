<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Models\Store;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/api/store-suggestions', [StoreController::class, 'suggest']);

Route::get('/api/store-search', function (Request $request) {
    $store = Store::where('name', $request->query('name'))->first();

    return response()->json([
        'exists' => (bool) $store,
        'store' => $store,
    ]);
});

Route::get('/api/cheapest-transaction/{productId}', [TransactionController::class, 'cheapestTransaction']);



