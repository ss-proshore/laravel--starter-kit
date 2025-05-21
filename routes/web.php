<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        // $user = App\Models\User::find(4);
        // $user->assignRole('user manager');
        // dd($user->can('edit roles'));
        return Inertia::render('Dashboard');
    })->middleware('permission:view dashboard')->name('dashboard');

    Route::controller(RoleController::class)->prefix('/roles')->name('roles.')->group( function() {
        Route::get('/',  'index')
                ->middleware('permission:view roles')
                ->name('index');
        Route::get('/create',  'create')->middleware('permission:create roles')->name('create');
        Route::post('/',  'store')->middleware('permission:create roles')->name('store');
        Route::get('/{role}',  'show')->middleware('permission:view roles')->name('show');
        Route::get('/{role}/edit',  'edit')->middleware('permission:edit roles')->name('edit');
        Route::patch('/{role}',  'update')->middleware('permission:edit roles')->name('update');
        Route::delete('/{role}',  'destroy')->middleware('permission:delete roles')->name('destroy');
    });

    Route::controller(PermissionController::class)->prefix('/permissions')->name('permissions.')->group( function() {
        Route::get('/',  'index')
                ->middleware('permission:view permissions')
                ->name('index');
        Route::get('/create',  'create')->middleware('permission:create permissions')->name('create');
        Route::post('/',  'store')->middleware('permission:create permissions')->name('store');
        Route::get('/{permission}',  'show')->middleware('permission:view permissions')->name('show');
        Route::get('/{permission}/edit',  'edit')->middleware('permission:edit permissions')->name('edit');
        Route::patch('/{permission}',  'update')->middleware('permission:edit permissions')->name('update');
        Route::delete('/{permission}',  'destroy')->middleware('permission:delete permissions')->name('destroy');
    });

    Route::controller(UserController::class)->prefix('/users')->name('users.')->group( function() {
        Route::get('/',  'index')
                ->middleware('permission:view users')
                ->name('index');
        Route::get('/create',  'create')->middleware('permission:create users')->name('create');
        Route::post('/',  'store')->middleware('permission:create users')->name('store');
        Route::get('/{user}',  'show')->middleware('permission:view users')->name('show');
        Route::get('/{user}/edit',  'edit')->middleware('permission:edit users')->name('edit');
        Route::patch('/{user}',  'update')->middleware('permission:edit users')->name('update');
        Route::delete('/{user}',  'destroy')->middleware('permission:delete users')->name('destroy');
    });
    Route::get('/user', [UserController::class, 'userData'])->name('users.data');



    Route::view('/profile', 'profile/edit')
        ->name('profile.edit');




    Route::controller(ActivityLogController::class)->prefix('/activity-logs')->name('activity.')->group( function() {

        Route::get('/',  'index')
                ->middleware('permission:view activity')
                ->name('index');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
