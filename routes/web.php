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

    Route::get('admin/article/category', 'Api\ArticleController@categoryList');//分类列表
    Route::get('admin/article', 'Api\ArticleController@articleList');//文章列表
    Route::get('admin/article/info', 'Api\ArticleController@articleInfo');//文章详情
    Route::post('admin/article/add', 'Api\ArticleController@articleAdd');//添加文章
    Route::post('admin/article/edit', 'Api\ArticleController@articleEdit');//修改文章
    Route::get('admin/article/delete', 'Api\ArticleController@deleteArticle');//删除文章

});
