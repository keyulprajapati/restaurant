<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

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

    });

require __DIR__.'/auth.php';
