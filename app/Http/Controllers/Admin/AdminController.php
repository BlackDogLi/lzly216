<?php
/**
 * User: Ly
 * Date: 2018/1/9
 * Time: 12:36
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	/**
	 * Create a new controller instance
	 *
	 * @return void
	 */
	/*public function __construct ()
	{
		$this->middleware('auth.admin');
	}*/

	public function index ()
	{
		return view('admin.login');
	}

	public function lately ()
	{
		$response = [
			'posts' => 10,
			'comments' => 11,
			'post_trash' => 12,
			'recent_posts' => 13
		];
		return reponse()->json($response);
	}
}