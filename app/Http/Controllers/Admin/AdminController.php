<?php
/**
 * User: Ly
 * Date: 2018/1/9
 * Time: 12:36
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Articles;
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
		$posts = Articles::count();
		$post_trash = Articles::onlyTrashed()->count();
		$comments = Articles::sum('comments');
		$recent_posts = Articles::orderBy('created_at', 'desc')->take(5)->get(['id', 'title', 'created_at']);

		$response = [
				'posts' => $posts,
				'comments' => $comments,
				'post_trash' => $post_trash,
				'recent_posts' => $recent_posts
		];
		return response()->json($response);
	}
}