<?php

use App\Http\Controllers\AsetKritisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\MitigationController;
use App\Http\Controllers\KelemahanAsetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () { return view('welcome');})->name('home');

Route::group(['middleware' => 'auth'], function () {
    // Use the resource route for users
    Route::resource('users', UserController::class); 
    Route::resource('dashboard', DashboardController::class);
    Route::resource('products', ProductController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('transactions', TransactionController::class);
    
    // Dynamic page routes
    Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
