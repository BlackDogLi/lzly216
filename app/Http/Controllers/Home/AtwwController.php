<?php
/**
 * 一路向西.
 * User: ly
 * Date: 2018/7/16
 * Time: 16:02
 */

namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;

class AtwwController extends Controller
{
    public function index ()
    {
        return view('home.atww.index');
    }

}