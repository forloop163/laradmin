<?php

Route::resource('/system/users', 'System\UserController');
Route::put('/system/users/{id}/reset', 'System\UserController@reset')->name('users.reset');
Route::put('/system/users/{id}/freeze', 'System\UserController@freeze')->name('users.freeze');
