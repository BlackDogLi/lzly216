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

use App\Http\Controllers\Home\ArticleController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\AtwwController;
use App\Http\Controllers\Home\CommentController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategorysController;
use App\Http\Controllers\Admin\ArticlesController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OptionsController;
use App\Http\Controllers\Admin\UploadsController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\ImagePositionController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\ImgController;

/*
 * 前台路由
 * @author: Ly
 * @date: 2017-12-8
 */
Route::get('/', [HomeController::class, 'index']) -> name('home');
Route::get('article/{flag}', [ArticleController::class, 'articleDetail'])->name('article');
Route::get('articleList/{id}', [ArticleController::class, 'articleList'])->name('articleList');
Route::get('ctg', [ArticleController::class, 'index']);
Route::get('atww', [AtwwController::class, 'index']);
Route::get('comment', [CommentController::class, 'index']);
Route::post('comment', [CommentController::class, 'create']);

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::post('login/check', [LoginController::class, 'check'])->name('admin.check');
    Route::post('login/login', [LoginController::class,'login'])->name('admin.login');
    Route::post('login/logout', [LoginController::class, 'logout'])->name('admin.logout');
    Route::post('user/resetPassword', [UserController::class,'resetPassword']);
});

Route::prefix('back')->middleware(['auth.admin:admin'])->group(function () {
    Route::get('admin/lately', [AdminController::class, 'lately']);
    Route::resource('options', OptionsController::class);
    Route::resource('setting', SettingController::class);
    Route::resource('categorys', CategorysController::class);
    Route::resource('navigations', NavigationController::class);
    Route::resource('articles', ArticlesController::class);
    Route::resource('uploads', UploadsController::class);
    Route::resource('tags', TagsController::class);
    Route::resource('imgposition', ImagePositionController::class);
    Route::resource('comments', CommentsController::class);
    Route::resource('img', ImgController::class);
    Route::post('article/articleImg', [UploadsController::class, 'articleImg']);
});
