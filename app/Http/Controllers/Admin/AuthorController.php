<?php
/**
 * 后台权限控制
 * User: Ly
 * Date: 2017/12/8
 * Time: 15:21
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

Class AuthorController extends Controller{

    public function index(){
        return view('admin.login');
    }

    //登录
    public function login(){

    }
    //校验
    public function check(){
        echo 222;
    }
}