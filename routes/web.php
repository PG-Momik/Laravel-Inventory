<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
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

        Route::prefix('users')
            ->controller(CustomerController::class)
            ->name('users.')
            ->group(
                function () {
                    Route::any('/', 'index')->name('index');

                    Route::get('/add', 'create')->name('create');
                    Route::post('/add', 'store')->name('save');

                    Route::get('/show', 'show')->name('show');

                    Route::get('/edit', 'edit')->name('edit');

                    Route::post('/update', 'update')->name('update');

                    Route::get('/delete', 'delete')->name('delete');

                    Route::get('/transactions', 'showTransactions')->name('transactions');
                }
            );

        Route::prefix('roles')
            ->controller(RoleController::class)
            ->name('roles.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');

                    Route::get('/add', 'create')->name('create');
                    Route::post('/add', 'save')->name('save');

                    Route::get('/show', 'show')->name('show');

                    Route::get('/edit', 'edit')->name('edit');

                    Route::put('/update', function () {
                            return "hello world";
                        }
                    )->name('update');

                    Route::get('delete', 'delete')->name('delete');
                }
            );

        Route::prefix('products')
            ->controller(ProductController::class)
            ->name('products.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');

                    Route::get('/add', 'create')->name('create');
                    Route::post('/add', 'save')->name('save');

                    Route::get('/show', 'show')->name('show');

                    Route::get('/edit', 'edit')->name('edit');

                    Route::get('/update', 'update')->name('update');

                    Route::get('delete', 'delete')->name('delete');
                }
            );

        Route::prefix('categories')
            ->controller(CategoryController::class)
            ->name('categories.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');


                    Route::get('/add', 'create')->name('create');
                    Route::post('/add', 'save')->name('save');

                    Route::get('/show', 'show')->name('show');

                    Route::get('/edit', 'edit')->name('edit');

                    Route::get('/update', 'update')->name('update');

                    Route::get('delete', 'delete')->name('delete');
                }
            );

        Route::prefix('activities')
            ->controller(TransactionController::class)
            ->name('transactions.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');

                    Route::get('/add', 'create')->name('create');
                    Route::post('/add', 'save')->name('save');

                    Route::get('/show', 'show')->name('show');

                    Route::get('/edit', 'edit')->name('edit');

                    Route::get('/update', 'update')->name('update');

                    Route::get('delete', 'delete')->name('delete');
                }
            );

        Route::prefix('profile')
            ->controller(ProfileController::class)
            ->name('profile.')
            ->group(
                function () {
                    Route::get('/', 'index')->name('index');
                }
            );
    }
);

