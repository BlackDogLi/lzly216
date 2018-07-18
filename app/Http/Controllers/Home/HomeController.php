<?php

/**
 * 前台首页控制器.
 * User: Ly
 * Date: 2017/12/16
 * Time: 17:10
 */

namespace App\Http\Controllers\Home;

use App\Models\Articles;
use App\Models\Navications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Event;
use App\Events\ArticleView;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        //文章
        $article = Articles::orderBy('created_at', 'Desc')->select('id', 'title','flag', 'content', 'markdown')->limit(5)->get();

        //PHP文章分类
        $phpArticle = Articles::orderBy('created_at', 'Desc')->where('category_id',  2)->select('id', 'title', 'flag' )->limit(5)->get();

        //server文章分类
        $serverArticle = Articles::orderBy('created_at', 'Desc')->where('category_id',  3)->select('id', 'title', 'flag')->limit(5)->get();

        //data文章分类
        $dataArticle = Articles::orderBy('created_at', 'Desc')->where('category_id',  4)->select('id', 'title', 'flag')->limit(5)->get();

        //hot文章分
        $hotArticle = Articles::orderBy('views', 'Desc')->select('id', 'title', 'flag')->limit(10)->get();
        return view('home.welcome', ['phpArticle' => $phpArticle, 'article' => $article, 'serverArticle' => $serverArticle, 'dataArticle' => $dataArticle, 'hotArticle' => $hotArticle]  );
    }

    /**
     * @Desc: 文章详情
     * @param $flag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail ($flag)
    {

        $articleDetail = Articles::select('id', 'title', 'flag', 'content', 'views')->where('flag', '=', $flag)->first();
        Event::fire(new ArticleView($articleDetail));
        return view('home.articledetail', ['articleDetail' => $articleDetail]);
    }

}