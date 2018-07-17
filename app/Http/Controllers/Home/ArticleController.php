<?php
/**
 * 采菊园控制器.
 * User: ly
 * Date: 2018/7/16
 * Time: 11:01
 */

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;


class ArticleController extends Controller
{
    public function index ()
    {
        return view('home.caijuyuan.index');
    }
}