<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;



// Route::group(['prefix' => 'admin',], function () {

Route::get('/', [AuthController::class, 'login']);

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/ajax-signup', [AuthController::class, 'ajaxSignup'])->name('ajax.signup');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('Dashboard');

    Route::get('/access-denied', function () { return view('access-denied'); })->name('access.denied');

    Route::get('/menu-editor', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/load', [MenuController::class, 'load'])->name('menu.load');
    Route::post('/menu/save', [MenuController::class, 'save'])->name('menu.save');

    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');

    Route::get('/modules/permissions/get', [ModuleController::class, 'getPermissions'])->name('modules.permissions.get');
    Route::post('/modules/permissions/save', [ModuleController::class, 'savePermission'])->name('modules.permissions.save');


    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');

    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');

    Route::get('/roles/setpermission/{id}', [RoleController::class, 'setPermission'])->whereNumber('id')->name('roles.setpermission');

    Route::post('/roles/savepermissions/{id}', [RoleController::class, 'savePermissions'])->whereNumber('id')->name('roles.savepermissions');

    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->whereNumber('id')->name('roles.edit');

    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->whereNumber('id')->name('roles.update');

    Route::get('/roles/delete/{id}', [RoleController::class, 'delete'])->whereNumber('id')->name('roles.delete');


    /* ==============================
       Users Management
    ============================== */

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('users/create', [UserController::class, 'create'])->name('users.create');

    Route::post('users/store', [UserController::class, 'store'])->name('users.store');

    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

    Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');


    /* ==============================
       User Permissions
    ============================== */

    Route::get('users/permissions/{id}', [UserController::class, 'permissions'])->name('users.permissions');

    Route::post('users/savepermissions/{id}', [UserController::class, 'savePermissions'])->name('users.savePermissions');

    /* ==============================
       Profile
    ============================== */

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('change-password', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::post('change-password', [ProfileController::class, 'updatePassword'])->name('password.update');




});
// });


