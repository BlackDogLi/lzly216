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
        //导航
        $nav = Navications::where('isShow', '=', 1)->orderBy('sort', 'asc')->get();
        //文章
        $article = Articles::orderBy('created_at', 'Desc')->select('id', 'title','flag', 'content', 'markdown')->limit(5)->get();
        //$article['markdown'] = htmlspecialchars($article['markdown']);
        return view('home.welcome', ['nav' => $nav, 'article' => $article] );
    }
    public function articleDetail ($flag)
    {
        //导航
        $nav = Navications::where('isShow', '=', 1)->orderBy('sort', 'asc')->get();
        $articleDetail = Articles::select('id', 'title', 'flag', 'content')->where('flag', '=', $flag)->first();
        $current = Route::current();
        print_r($current);exit();
        return view('home.articledetail', ['nav' => $nav, 'articleDetail' => $articleDetail]);
    }

}