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

// Route::get('/', function () {
//     return view('welcome');
// });

//api
Route::prefix('api/')->group(function () {
    Route::get('test', 'Api\IndexController@index');
    Route::post('post', 'Api\IndexController@testPost');

    Route::post('admin/register', 'Api\AdminUserController@register');//注册
    Route::post('admin/login', 'Api\AdminUserController@login');//登录
    Route::get('admin/user', 'Api\AdminUserController@getAuthUser');//用户信息

    Route::get('admin/article/category', 'Api\ArticleController@categoryList');//分类列表
    Route::get('admin/article', 'Api\ArticleController@articleList');//文章列表
    Route::get('admin/article/info', 'Api\ArticleController@articleInfo');//文章详情

});

Route::middleware('jwt.api.auth')->prefix('api/admin/')->group(function () {
    Route::post('article/add', 'Api\ArticleController@articleAdd');//添加文章
    Route::post('article/edit', 'Api\ArticleController@articleEdit');//修改文章
    Route::get('article/delete', 'Api\ArticleController@deleteArticle');//删除文章


    Route::get('rule/list', 'Api\RuleController@ruleList'); // 查询权限
    Route::get('rule/ruleAuthFindById', 'Api\RuleController@ruleAuthFindById'); // 查询菜单权限
    Route::post('rule/save', 'Api\RuleController@ruleSave'); // 添加权限
    Route::post('rule/update', 'Api\RuleController@ruleUpdate'); // 修改权限
    Route::get('rule/delete', 'Api\RuleController@ruleDelete'); // 删除权限

    Route::get('role/list', 'Api\RuleController@roleList'); // 查询权限
    Route::post('role/save', 'Api\RoleController@roleSave'); // 添加角色
    Route::post('role/update', 'Api\RoleController@roleUpdate'); // 修改角色
    Route::get('role/delete', 'Api\RoleController@roleDelete'); // 删除角色
    Route::get('role/getRoleRuleList', 'Api\RoleController@getRoleRuleList'); // 获取角色权限
});
