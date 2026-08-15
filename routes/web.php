<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantTableController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});


Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {

            return view('admin.dashboard');

        })
            ->middleware('permission:dashboard.view')
            ->name('dashboard');
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/categories',
            [CategoryController::class, 'index']
        )
            ->middleware('permission:categories.view')
            ->name('categories.index');


        Route::get(
            '/categories/create',
            [CategoryController::class, 'create']
        )
            ->middleware('permission:categories.create')
            ->name('categories.create');


        Route::post(
            '/categories',
            [CategoryController::class, 'store']
        )
            ->middleware('permission:categories.create')
            ->name('categories.store');


        Route::get(
            '/categories/{category}/edit',
            [CategoryController::class, 'edit']
        )
            ->middleware('permission:categories.edit')
            ->name('categories.edit');


        Route::put(
            '/categories/{category}',
            [CategoryController::class, 'update']
        )
            ->middleware('permission:categories.edit')
            ->name('categories.update');


        Route::delete(
            '/categories/{category}',
            [CategoryController::class, 'destroy']
        )
            ->middleware('permission:categories.delete')
            ->name('categories.destroy');
        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [UserController::class, 'index']
        )
            ->middleware('permission:users.view')
            ->name('users.index');

        Route::get(
            '/users/create',
            [UserController::class, 'create']
        )
            ->middleware('permission:users.create')
            ->name('users.create');

        Route::post(
            '/users',
            [UserController::class, 'store']
        )
            ->middleware('permission:users.create')
            ->name('users.store');

        Route::get(
            '/users/{user}/edit',
            [UserController::class, 'edit']
        )
            ->middleware('permission:users.edit')
            ->name('users.edit');

        Route::put(
            '/users/{user}',
            [UserController::class, 'update']
        )
            ->middleware('permission:users.edit')
            ->name('users.update');

        Route::delete(
            '/users/{user}',
            [UserController::class, 'destroy']
        )
            ->middleware('permission:users.delete')
            ->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | Role Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/roles',
            [RoleController::class, 'index']
        )
            ->middleware('permission:roles.view')
            ->name('roles.index');

        Route::get(
            '/roles/create',
            [RoleController::class, 'create']
        )
            ->middleware('permission:roles.create')
            ->name('roles.create');

        Route::post(
            '/roles',
            [RoleController::class, 'store']
        )
            ->middleware('permission:roles.create')
            ->name('roles.store');

        Route::get(
            '/roles/{role}/edit',
            [RoleController::class, 'edit']
        )
            ->middleware('permission:roles.edit')
            ->name('roles.edit');

        Route::put(
            '/roles/{role}',
            [RoleController::class, 'update']
        )
            ->middleware('permission:roles.edit')
            ->name('roles.update');

        Route::delete(
            '/roles/{role}',
            [RoleController::class, 'destroy']
        )
            ->middleware('permission:roles.delete')
            ->name('roles.destroy');


        /*
        |--------------------------------------------------------------------------
        | Permission Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/permissions',
            [PermissionController::class, 'index']
        )
            ->middleware('permission:roles.view')
            ->name('permissions.index');

        Route::get(
            '/products',
            [ProductController::class, 'index']
        )
            ->middleware('permission:products.view')
            ->name('products.index');

        Route::get(
            '/products/create',
            [ProductController::class, 'create']
        )
            ->middleware('permission:products.create')
            ->name('products.create');

        Route::post(
            '/products',
            [ProductController::class, 'store']
        )
            ->middleware('permission:products.create')
            ->name('products.store');

        Route::get(
            '/products/{product}/edit',
            [ProductController::class, 'edit']
        )
            ->middleware('permission:products.edit')
            ->name('products.edit');

        Route::put(
            '/products/{product}',
            [ProductController::class, 'update']
        )
            ->middleware('permission:products.edit')
            ->name('products.update');

        Route::delete(
            '/products/{product}',
            [ProductController::class, 'destroy']
        )
            ->middleware('permission:products.delete')
            ->name('products.destroy');

        Route::get(
            '/combos',
            [ComboController::class, 'index']
        )
            ->middleware('permission:combos.view')
            ->name('combos.index');

        Route::get(
            '/combos/create',
            [ComboController::class, 'create']
        )
            ->middleware('permission:combos.create')
            ->name('combos.create');

        Route::post(
            '/combos',
            [ComboController::class, 'store']
        )
            ->middleware('permission:combos.create')
            ->name('combos.store');

        Route::get(
            '/combos/{combo}/edit',
            [ComboController::class, 'edit']
        )
            ->middleware('permission:combos.edit')
            ->name('combos.edit');

        Route::put(
            '/combos/{combo}',
            [ComboController::class, 'update']
        )
            ->middleware('permission:combos.edit')
            ->name('combos.update');

        Route::delete(
            '/combos/{combo}',
            [ComboController::class, 'destroy']
        )
            ->middleware('permission:combos.delete')
            ->name('combos.destroy');

        Route::get(
            '/tables',
            [RestaurantTableController::class, 'index']
        )
            ->middleware('permission:tables.view')
            ->name('tables.index');

        Route::get(
            '/tables/create',
            [RestaurantTableController::class, 'create']
        )
            ->middleware('permission:tables.create')
            ->name('tables.create');

        Route::post(
            '/tables',
            [RestaurantTableController::class, 'store']
        )
            ->middleware('permission:tables.create')
            ->name('tables.store');

        Route::get(
            '/tables/{table}/edit',
            [RestaurantTableController::class, 'edit']
        )
            ->middleware('permission:tables.edit')
            ->name('tables.edit');

        Route::put(
            '/tables/{table}',
            [RestaurantTableController::class, 'update']
        )
            ->middleware('permission:tables.edit')
            ->name('tables.update');

        Route::delete(
            '/tables/{table}',
            [RestaurantTableController::class, 'destroy']
        )
            ->middleware('permission:tables.delete')
            ->name('tables.destroy');

    });

require __DIR__ . '/auth.php';
