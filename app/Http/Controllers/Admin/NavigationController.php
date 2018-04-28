<?php
/**
 * 导航管理
 * User: Ly
 * Date: 2018/2/27
 * Time: 17:26
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Factorymethod\Interfaces\FactoryInterface;

class NavigationController extends Controller implements FactoryInterface
{
	private $_response = [];

	public function creatorFail ($error)
	{
		$this->_response = ['status' => 'error', 'info' => $error];
	}


	public function creatorSuccess ($model)
	{
		$this->_response = ['status' => 'success', 'info' => '操作成功'];
	}

	public function transform ($data)
	{
		return !empty($data->set_value) ? json_decode($data->set_value, true) : [];
	}

	public function index()
	{
		$listData = Setting::firstOrCreate(['set_name' => 'navigations']);
		$listData = $this->transform($listData);
		return response()->json($listData);
	}

	public function update(Request $request)
	{
		$post = $request->all();
		$settings = Setting::firstOrCreate(['set_name' => 'navigations']);
		$settings->set_value = json_encode($post);
		$response = $settings->save();
		return response()->json(['status' => !$response ? 'error' : 'success']);
	}
}