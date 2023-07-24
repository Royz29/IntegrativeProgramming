<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\InventoryWebServiceController;

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


Route::get('/users/id/{id}', [UserApiController::class, 'searchById']);
Route::get('/users/name/{name}', [UserApiController::class, 'searchByName']);
Route::get('/client/users/name/{name}', [UserApiController::class, 'searchUserByNameClient']);
Route::get('/client/users/id/{id}', [UserApiController::class, 'searchByIdClient']);
Route::get('/userClient', [UserApiController::class, 'showUserClient']);

//================================= TAI SHAO YANG =============================
Route::get('/inventory', [InventoryWebServiceController::class, 'getAllInventory']);
Route::get('/inventory/name/{name}', [InventoryWebServiceController::class, 'searchByName']);
Route::get('/inventory/category/{category}', [InventoryWebServiceController::class, 'searchByCategory']);

//================================ SHIM YEN LEM ================================
