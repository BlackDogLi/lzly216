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

/*
 * 前台路由
 * @author: Ly
 * @date: 2017-12-8
 */
Route::group(['namespace' => 'Home'], function (){
    Route::get('/', 'HomeController@index') -> name('home');
});

/*
 * 后台路由
 * @author: Ly
 * @date: 2017-12-8
 */
/*Route::group(['prefix' => 'back', 'namespace' => 'back'], function(){
    Route::get('/', 'AuthorController@index')->name('admin');
    Route::post('/check', 'AuthorController@check')->name('admin.check');
    Route::post('/login', 'AuthorController@login')->name('admin.login');
});*/
Route::group(['prefix' => 'back', 'namespace' => 'Admin'], function (){
    Route::get('/', 'AdminController@index')->name('admin');
    Route::post('/login/check', 'LoginController@check')->name('admin.check');
    Route::post('/login/login', 'LoginController@login')->name('admin.login');
    Route::post('/login/logout', 'LoginController@logout')->name('admin.logout');
    Route::post('/user/resetPassword', 'UserController@resetPassword');
    //Route::get('index', 'AdminController@index');


});
Route::group(['prefix' => 'back', 'middleware' => 'auth.admin:admin', 'namespace' => 'Admin'], function () {
   // Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/admin/lately', 'AdminController@lately');
    Route::resource('/options', 'OptionsController');
});

