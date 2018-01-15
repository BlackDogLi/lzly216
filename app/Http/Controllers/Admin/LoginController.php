<?php
/**
 * User: Ly
 * Date: 2018/1/9
 * Time: 12:19
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
	//use AuthenticatesUsers;

	/**
	 *  where to redirect users after login/registration
	 *
	 * @var string
	 */

	protected $redirectTo = '/back';
	protected $username;



	/**
	 *  重写登录页面
	 */
	/*public function showLogin ()
	{
		return view('admin.login');
	}*/

	/**
	 * 自定义认证驱动
	 * @return mixed
	 */
	/*public function guard ()
	{
		return auth()->guard('admin');
	}*/

	//登录
	public function login(Request $request){
		$post = $request -> input();

		if (Auth::guard('admin')->attempt(['email' => $post['email'], 'password' => $post['password']])) {
			$user = Auth::guard('admin')->user();
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
		if (Auth::guard('admin')->check()) {
			return ['auth' => 'Authenticated'];
		}
		return ['auth' => 'Unauthenticated'];
	}

	//登出
	public function logout() {
		Auth::guard('admin')->logout();
		$data = [
			'status' => 200,
			'info' => '退出成功'
		];
		return response() -> json($data);
	}
}