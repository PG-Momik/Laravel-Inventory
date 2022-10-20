<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ProductController;
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

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Route::get(
    '/home',
    function () {
        return redirect('dashboard');
    }
)->name('home');

Route::middleware(['auth'])->group(
    function () {
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

        Route::get('/users/transactions/{id}', [UserController::class, 'showTransactions'])->name('users.transactions');

        Route::match(['get', 'post'], '/users/trash', [UserController::class, 'showTrash'])->name('users.trashed');

        Route::get('/users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');

        Route::get('/users/delete/{id}', [UserController::class, 'hardDelete'])->name('users.delete');

        Route::resource('users', UserController::class);


        Route::resource('roles', RoleController::class);



        Route::match(['get', 'post'], '/products/trash', [ProductController::class, 'showTrash'])->name('products.trashed');

        Route::get('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');

        Route::get('/products/delete/{id}', [ProductController::class, 'hardDelete'])->name('products.delete');

        Route::resource('products', ProductController::class);


        Route::resource('categories', CategoryController::class);


        Route::resource('transactions', TransactionController::class);

        Route::resource('profile', TransactionController::class);
    }
);

