<?php


Route::resource('/system/roles', 'System\RoleController');
Route::put('/system/roles/{id}/reset', 'System\RoleController@reset')->name('roles.reset');
Route::put('/system/roles/{id}/freeze', 'System\RoleController@freeze')->name('roles.freeze');
Route::get('/system/role_dict', 'System\RoleController@roles')->name('roles.role_dict');
Route::put('/system/role_permission/{id}', 'System\RoleController@setRolePermissions')->name('roles.role_permission');

