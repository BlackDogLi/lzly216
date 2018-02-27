<?php
/**
 * User: Ly
 * Date: 2018/2/27
 * Time: 13:44
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilController extends Controller {

	public function index (Request $request) {
		$data = [];
		if (in_array($request->action, get_class_methods($this))) {
			$data = call_user_func_array([$this, $request->action], [$request->toArray()]);
		}
		return response()->json($data);
	}
}

//百度翻译