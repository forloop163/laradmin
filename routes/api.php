<?php

use Illuminate\Http\Request;

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
Auth::routes();

Route::middleware(['api', 'auth:web'])->group(function () {
    // auth
    Route::get('/auth/user/info', '\App\Http\Controllers\Auth\UserController@userInfo')->name('user.user_info');
});

Route::middleware(['api', 'auth:web', 'laradmin'])->group(function () {
    // 后台用户
    Route::resource('/system/users', '\App\Http\Controllers\System\UserController');
    Route::put('/system/users/{id}/reset', '\App\Http\Controllers\System\UserController@reset')->name('users.reset');
    Route::put('/system/users/{id}/freeze', '\App\Http\Controllers\System\UserController@freeze')->name('users.freeze');

    // 后台角色
    Route::resource('/system/roles', '\App\Http\Controllers\System\RoleController');
    Route::put('/system/roles/{id}/reset', '\App\Http\Controllers\System\RoleController@reset')->name('roles.reset');
    Route::put('/system/roles/{id}/freeze', '\App\Http\Controllers\System\RoleController@freeze')->name('roles.freeze');
    Route::get('/system/role_dict', '\App\Http\Controllers\System\RoleController@roles')->name('roles.role_dict');
    Route::put('/system/role_permission/{id}', '\App\Http\Controllers\System\RoleController@setRolePermissions')->name('roles.role_permission');

    // 后台权限
    Route::resource('/system/permissions', '\App\Http\Controllers\System\PermissionController');
    Route::post('/system/permissions/node_drop', '\App\Http\Controllers\System\PermissionController@nodeDrop')->name('permissions.node_drop');
});
