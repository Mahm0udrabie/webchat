<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'App\Http\Controllers\UsersController@login');
Route::post('auth', 'App\Http\Controllers\UsersController@auth_user');
Route::get('register', 'App\Http\Controllers\UsersController@register');
Route::post('store', 'App\Http\Controllers\UsersController@store');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', 'App\Http\Controllers\UsersController@home');
});

