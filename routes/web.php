<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
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
            ->name('dashboard.')
            ->group(
                function () {
                    Route::get('/', [DashboardController::class, 'index'])->name('index');

                    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');

                    Route::get('/test', [DashboardController::class, 'test'])->name('test');

                    Route::get(
                        '/data-for-line-graph/{type?}',
                        [DashboardController::class, 'getValuesForLineGraph']
                    )->name('line-data');
                    Route::get(
                        '/data-for-pie-chart/{type?}',
                        [DashboardController::class, 'getValuesForPieChart']
                    )->name('pie-data');

                }
            );

        Route::get('/users/transactions/{id}', [UserController::class, 'showTransactions'])->name('users.transactions');

        Route::match(['get', 'post'], '/users/trash', [UserController::class, 'showTrash'])->name('users.trashed');

        Route::get('/users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');

        Route::get('/users/delete/{id}', [UserController::class, 'hardDelete'])->name('users.delete');

        Route::post('/users/search', [UserController::class, 'index'])->name('users.search');

        Route::resource('users', UserController::class);


        Route::get('roles/demote/{uid}', [RoleController::class, 'demote'])->name('roles.demote');
        Route::get('roles/promote/{uid}', [RoleController::class, 'promote'])->name('roles.promote');

        Route::resource('roles', RoleController::class);


        Route::match(['get', 'post'], '/products/trash', [ProductController::class, 'showTrash'])->name(
            'products.trashed'
        );

        Route::get('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');

        Route::get('/products/delete/{id}', [ProductController::class, 'hardDelete'])->name('products.delete');

        Route::post('/products/search', [ProductController::class, 'index'])->name('products.search');

        Route::resource('products', ProductController::class);

        Route::post('/categories/search', [CategoryController::class, 'index'])->name('categories.search');
        Route::resource('categories', CategoryController::class);


        Route::get('transactions/show/{type}', [TransactionController::class, 'showTransactions'])->name(
            'show-transactions'
        );

        Route::get('/generate-pdf/{transaction}', [TransactionController::class, 'createPDF'])->name('generate-pdf');

        Route::resource('transactions', TransactionController::class);


        Route::resource('profile', ProfileController::class);


        Route::get('/ajax/category/{id}/products', [AjaxController::class, 'categoryProducts']);
        Route::post('/ajax/products/filter', [AjaxController::class, 'filterProducts'])->name('filterProducts');
        Route::get('/ajax/getstats/category/{id}/{detailed?}', [AjaxController::class, 'getCategoryBasedStats'])
            ->name('category-stats');
        Route::get('/ajax/getstats/roles', [AjaxController::class, 'getRoleBasedStats'])->name('roles-stats');
    }
);

