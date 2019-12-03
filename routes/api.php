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
