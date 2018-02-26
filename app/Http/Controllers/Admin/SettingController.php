<?php
/**.
 * 网站设置控制器
 * User: Ly
 * Date: 2018/1/16
 * Time: 11:12
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
	/*
	 * get setting
	 * @param Request $request
	 */
	public function index ()
	{
		$listData = Setting::nothidden()->get();
		return response()->json($listData);
	}

	/*
	 * Update setting
	 * @param   Request $request
	 */

	public function update (Request $request)
	{
		$post = $request->all();
		foreach ($post as $key => $set) {
			$model = Setting::firstOrNew(['set_name' => $key]);
			$model->set_value = $set;
			$model->save();
			unset($model);
		}
		//redis、memcache Update
		return response()->json(['status' => 'success']);
	}
}

