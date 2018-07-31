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
use App\Models\Navications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use App\Events\ArticleView;
use App\Http\Controllers\Controller;

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
            return Articles::select('id', 'title', 'flag' )->with(['categories' => function($query){$query->where('id', 2)->orwhere('category_parent', 2);}])->limit(10)->orderBy('created_at', 'Desc')->get();
        });

        //server文章分类
        $serverArticle = Cache::remember('ServerArticle', 10 , function () {
            return Articles::where('category_id',  3)->select('id', 'title', 'flag')->limit(5)->orderBy('created_at', 'Desc')->get();
        });

        //data文章分类
        $dataArticle = Cache::remember('DataArticle', 10, function () {
            return Articles::where('category_id',  4)->select('id', 'title', 'flag')->limit(5)->orderBy('created_at', 'Desc')->get();
        });

        //hot文章分
        $hotArticle = Cache::remember('HotArticle', 10, function () {
            return Articles::select('id', 'title', 'flag')->limit(10)->orderBy('views', 'Desc')->get();
        });

        return view('home.welcome', ['phpArticle' => $phpArticle, 'article' => $article, 'serverArticle' => $serverArticle, 'dataArticle' => $dataArticle, 'hotArticle' => $hotArticle]  );
    }

    /**
     * @Desc: 文章详情
     * @param $flag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail ($flag)
    {
        $articleDetail = Cache::remember('ArticleDetail-' . $flag, 10, function () {
            Articles::select('id', 'title', 'flag', 'content', 'views')->where('flag', '=', $flag)->first();
        });
        Event::fire(new ArticleView($articleDetail));
        return view('home.articledetail', ['articleDetail' => $articleDetail]);
    }

}