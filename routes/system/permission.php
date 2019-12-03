<?php

Route::resource('/system/permissions', 'System\PermissionController');
Route::post('/system/permissions/node_drop', 'System\PermissionController@nodeDrop')->name('permissions.node_drop');
