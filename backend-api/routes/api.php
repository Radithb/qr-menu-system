<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

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

Route::post('/outlet/validate', [OutletController::class, 'validateCode']);
Route::get('/outlets', [OutletController::class, 'index']);
Route::get('/outlet/{outlet_id}/menus', [MenuController::class, 'getMenuByOutlet']);
Route::get('/outlets/{outlet_id}/orders', [OrderController::class, 'index']);
Route::get('/outlets/{outlet_id}/orders/history', [OrderController::class, 'history']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/orders/{id}/pay', [OrderController::class, 'markAsPaid']);
Route::get('/transactions/history', [OrderController::class, 'transactionHistory']);

// Menu CRUD
Route::get('/menus', [MenuController::class, 'index']);
Route::post('/menus', [MenuController::class, 'store']);
Route::post('/menus/{id}', [MenuController::class, 'update']);
Route::put('/menus/{id}', [MenuController::class, 'update']);
Route::delete('/menus/{id}', [MenuController::class, 'destroy']);
