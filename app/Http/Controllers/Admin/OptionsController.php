<?php
/**
 * 网站配置项管理
 * User: Ly
 * Date: 2018/1/19
 * Time: 10:47
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Factorymethod\Factory\SettingFactory;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Factorymethod\Interfaces\FactoryInterface;

class OptionsController extends Controller implements FactoryInterface
{

	private $_response = [];

	public function creatorFail ($error)
	{
		// TODO: Implement creatorFail() method.
		$this->_response = ['status' => 'error', 'info' => $error];
	}

	public function creatorSuccess ($model)
	{
		// TODO: Implement creatorSuccess() method.
		$this->_response = ['status' => 'success', 'info' => '操作成功'];
	}

	//Display a listing of the resource
	public function index (Request $request)
	{
		$rows = intval($request->rows) > 0 ? $request->rows : 20;
		$listData = Setting::paginate($rows);
		return response()->json($listData);
	}

	//Store data
	public function store (Request $request)
	{
		$this->validate($request, [
			'set_title' => 'required',
			'set_name' => 'required'
		]);
		$set = app(SettingFactory::class);
		$set->create($this, $request);
		return response()->json($this->_response);
	}

	/* Update a  data
	 * @param Request $request
	 * @param $id
	 */
	public function update (Request $request)
	{
		$this->validate($request, [
			'set_title' => 'required',
			'set_name' => 'required'
		]);
		app(SettingFactory::class)->update($this, $request);
		return response()->json($this->_response);
	}

	/*
	 * Display the specified resource
	 * @param   int $id
	 * @return  Response
	 */

	public function show ($id)
	{
		$data = Setting::find($id);
		return response()->json($data);
	}

	/*
	 * Remove the spectified from datas
	 * @param   int $id
	 * @return  Request
	 */
	public function destory (Request $request)
	{
		if (empty($request->ids)) {
			return response()->json(['status' => 'error', 'info' => 'ID不能为空']);
		}
		$result = Setting::notbase()->whereIn('id', $request->ids)->delete();
		return response()->json(['status' => !$request ? 'error' : 'success']);
	}

}
