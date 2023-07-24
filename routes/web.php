<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAuditLogController;
use App\Http\Controllers\UserSearchController;

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryActivityLogController;
use App\Http\Controllers\InventoryClientController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WebServiceController;
use App\Http\Controllers\PaymentHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::controller(UserController::class)->group(function () {

    Route::get('login', 'index')->name('login');

    Route::get('registration', 'registration')->name('registration');

    Route::put('/update/{id}', 'update');

    Route::get('adminPanel', 'adminPanel')->name('adminPanel');

    Route::get('logout', 'logout')->name('logout');

    Route::post('validate_registration', 'validate_registration')->name('user.validate_registration');

    Route::post('validate_login', 'validate_login')->name('user.validate_login');

    Route::get('dashboard', 'dashboard')->name('dashboard');

    Route::delete('/user/delete/{id}', 'delete')->name('user.delete');

    Route::get('api/users', 'showAllUser');

    Route::get('api/client/users', 'showAllUserClient');

    Route::post('/uploadImage', 'uploadImage');
});


Route::controller(UserAuditLogController::class)->group(function () {
    Route::get('user_audit_logs', 'index')->name('user_audit_logs');
});


// Route::post('/uploadImage', [UserSearchController::class, 'uploadImage']);


//=================================== TAI SHAO YANG =====================================================
Route::controller(InventoryController::class)->group(function () {
    Route::get('inventory', 'index')->name('inventory.index');
    Route::get('inventory/create', 'displayCreatePage')->name('inventory.create');
    Route::post('inventory/create', 'storeInventory')->name('inventory.store');
    Route::get('inventory/edit/{id}', 'displayEditPage')->name('inventory.edit');
    Route::put('inventory/edit/{id}', 'updateInventory')->name('inventory.update');
    Route::delete('inventory/delete/{id}', 'deleteInventory')->name('inventory.delete');
});

Route::controller(InventoryActivityLogController::class)->group(function () {
    Route::get('inventory/activityLog', 'index')->name('inventory.activityLog');
});

Route::controller(InventoryClientController::class)->group(function () {
    Route::get('/client/inventory', 'getAllInventory');
    Route::get('/client/inventory/name/{name}', 'searchByName');
    Route::get('/client/inventory/category/{category}', 'searchByCategory');
});


//======================================== SHIM YEN LEM =====================================================
Route::get('/products', [ProductController::class, 'index'])->name('products.product');
Route::get('/products/search', [ProductController::class, 'index'])->name('products.search');
Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::put('/cart/update/{cart}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::delete('/cart/delete/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/payment-history', [PaymentHistoryController::class, 'show'])->name('cart.receipt');

//web services
//Cart Web Service
Route::get('/apiCart/getAllCarts', [WebServiceController::class, 'getAllCarts'])->name('webapi.cartWeb');
Route::get('/apiCart/getCartsByName/{name}', [WebServiceController::class, 'getCartsByName'])->name('webapi.cartWeb');
Route::get('/apiCart/getCartByUserId/{id}', [WebServiceController::class, 'getCartByUserId'])->name('webapi.cartWeb');
Route::get('/apiCart/getCartById/{id}', [WebServiceController::class, 'getCartById'])->name('webapi.cartWeb');
Route::get('/apiCart/getCartByDate/{date}', [WebServiceController::class, 'getCartByDate'])->name('webapi.cartWeb');

//Product Web service
Route::get('/apiProduct/getAllProduct', [WebServiceController::class, 'getAllProducts'])->name('webapi.cartWeb');
Route::get('/apiProduct/getProductByName/{name}', [WebServiceController::class, 'getProductByName'])->name('webapi.cartWeb');
Route::get('/apiProduct/getProductByCategory/{category}', [WebServiceController::class, 'getProductByCategory'])->name('webapi.cartWeb');

Route::get('/showAllCarts', [WebServiceController::class, 'showAllCartWeb'])->name('webapi.showAllCarts');
