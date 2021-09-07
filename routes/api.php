<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExpenseController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/customers/search', [CustomerController::class, 'search']);
Route::get('/customers/live/search', [CustomerController::class, 'livesearch']);
Route::get('/customers/get/total_rows', [CustomerController::class, 'totalRows']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/store', [CustomerController::class, 'store']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);

Route::resource('/invoices', InvoiceController::class);

Route::resource('/products', ProductController::class);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/live/search', [ProductController::class, 'livesearch']);
Route::get('/products/get/total_rows', [ProductController::class, 'totalRows']);

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);

Route::resource('/items', ItemController::class);
Route::get('/items/search', [ItemController::class, 'search']);
Route::get('/items/live/search', [ItemController::class, 'livesearch']);
Route::get('/items/get/total_rows', [ItemController::class, 'totalRows']);

Route::resource('/expenses', ExpenseController::class);

Route::resource('/vendors', VendorController::class);
Route::get('/search/vendors', [VendorController::class, 'search']);
Route::get('/vendors/live/search', [VendorController::class, 'livesearch']);
Route::get('/vendors/get/total_rows', [VendorController::class, 'totalRows']);

