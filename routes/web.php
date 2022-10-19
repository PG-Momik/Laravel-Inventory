<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
        return view('welcome');
    }
);

Route::get('/home', function () {
        return redirect('dashboard');
    }
)->name('home');

Route::middleware(['auth'])->group(function () {

        Route::prefix('dashboard')
            ->controller(DashboardController::class)
            ->name('dashboard.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');

                    Route::get('/logout', 'logout')->name('logout');

                    Route::get('/test', 'test')->name('test');
                }
            );

        Route::resource('roles', RoleController::class);

        Route::resource('users', UserController::class);

        Route::resource('products', ProductController::class);

        Route::resource('categories', CategoryController::class);

        Route::resource('transactions', TransactionController::class);

        Route::resource('profile', TransactionController::class);

        Route::get('users/transactions', [UserController::class, 'showTransactions'])->name('users.transactions');
    }
);

