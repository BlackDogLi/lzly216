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
    public function login(Request $request){
        $post = $request -> input();
        if (Auth::attempt(['email' => $post['email'], 'password' => $post['password']])) {
            $user = Auth::user();
            $data = [
                'status' => 200,
                'info' => '登录成功',
                'user' => [
                    'name' => $user['name'],
                    'lzly' => Session::getId()
                ]
            ];
        } else {
            $data = [
                'status' => 403,
                'info' => '用户名或者密码不正确'
            ];
        }
        return response() -> json($data);
    }
    //校验
    public function check(){
        if (Auth::check()) {
            return ['auth' => 'Authenticated'];
        }
        return ['auth' => 'Unauthenticated'];
    }

    //登出
    public function logout() {
        Auth::logout();
        $data = [
            'status' => 200,
            'info' => '退出成功'
        ];
        return response() -> json($data);
    }
}