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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// 用户验证
Route::post('register', 'Api\UserController@register');//注册
Route::post('login', 'Api\UserController@login');//登录
Route::get('logout', 'Api\UserController@logout');//退出
Route::get('user', 'Api\UserController@getAuthUser');//用户信息
