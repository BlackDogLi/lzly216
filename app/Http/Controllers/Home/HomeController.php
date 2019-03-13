<?php

/**
 * 前台首页控制器.
 * User: Ly
 * Date: 2017/12/16
 * Time: 17:10
 */

namespace App\Http\Controllers\Home;

use App\Models\Articles;
use App\Models\Categorys;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {

        //文章
        $article = Cache::remember('articleT', 10, function() {
           return Articles::select('id', 'title','flag', 'content', 'markdown')->limit(5)->orderBy('created_at', 'Desc')->get();
        });

        //PHP文章分类
        $phpArticle = Cache::remember('PhpArticle', 10, function () {
            return DB::select('SELECT a.id, a.title, a.flag FROM lzly_articles AS a INNER JOIN lzly_categorys as c ON c.id = a.category_id WHERE c.id = ? OR c.category_parent = ?', [2, 2]);
        });

        //server文章分类
        $serverArticle = Cache::remember('ServerArticle', 10 , function () {
            return Articles::where('category_id',  3)->select('id', 'title', 'flag')->orderBy('created_at', 'Desc')->get();
        });

        //data文章分类
        $dataArticle = Cache::remember('DataArticle', 10, function () {
            return Articles::where('category_id',  4)->select('id', 'title', 'flag')->orderBy('created_at', 'Desc')->get();
        });

        //hot文章分
        $hotArticle = Cache::remember('HotArticle', 10, function () {
            return Articles::select('id', 'title', 'flag')->limit(10)->orderBy('views', 'Desc')->get();
        });

        return view('home.welcome', ['phpArticle' => $phpArticle, 'article' => $article, 'serverArticle' => $serverArticle, 'dataArticle' => $dataArticle, 'hotArticle' => $hotArticle]  );
    }
}