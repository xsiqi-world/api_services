<?php

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

//api
Route::prefix('api/')->group(function () {
    Route::get('test', 'Api\IndexController@index');
    Route::post('post', 'Api\IndexController@testPost');

    Route::post('admin/register', 'Api\IndexController@register');//注册
    Route::post('admin/login', 'Api\IndexController@login');//登录

});
