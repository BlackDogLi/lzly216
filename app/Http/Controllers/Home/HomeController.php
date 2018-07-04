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
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        //文章
        $article = Articles::orderBy('created_at', 'Desc')->select('id', 'title','flag', 'content', 'markdown')->limit(5)->get();
        //$article['markdown'] = htmlspecialchars($article['markdown']);

        return view('home.welcome', [ 'article' => $article] );
    }

    /**
     * @Desc: 文章详情
     * @param $flag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail ($flag)
    {

        $articleDetail = Articles::select('id', 'title', 'flag', 'content')->where('flag', '=', $flag)->first();
        return view('home.articledetail', ['articleDetail' => $articleDetail]);
    }

}