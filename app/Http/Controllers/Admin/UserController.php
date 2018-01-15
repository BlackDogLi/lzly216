<?php
/**
 * User: Ly
 * Date: 2018/1/11
 * Time: 16:51
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function index () {
		$user = Auth::guard('admin')->user();
		return reponse()->json($user);
	}

	/**
	 * 更新密码
	 * @param Request $request
	 * @return Response
	 */
	public function resetPassword (Request $request) {
		echo 111;
		die;
		$this -> validate($request, [
			'password' => 'required|min:6|confirmed',
			'password_confirmation' => 'required|min:6'
		]);
		$result = $request ->user()->fill([
			'password' => Hash::make($request->password)
		])->save();
		return response()->json(['status' => !$result ? 'error' : 'success']);
	}
}