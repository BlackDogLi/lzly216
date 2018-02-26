<?php
/**
 * 文章分类
 * User: Ly
 * Date: 2018/2/2
 * Time: 9:23
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorys;
use App\Factorymethod\Interfaces\FactoryInterface;
use App\Factorymethod\Factory\CategorysFactory;

class CategorysController extends Controller implements FactoryInterface
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
	//首页
	public function index (Request $request)
	{
		$rows = intval($request->rows) >0 ? $request->rows : 20;
		$listData = Categorys::paginate($rows);
		return response()->json($listData);
	}
	//Store a data
	public function store (Request $request)
	{
		$this->validate($request, [
			'category_name' => 'required',
			'category_flag' => 'required|flag'
		]);
		app(CategorysFactory::class)->create($this, $request);
		return response()->json($this->_response);
	}

	//Update a info of Category
	public function update (Request $request)
	{
		app(CategorysFactory::class)->update($this, $request);
		return response()->json($this->_response);
	}

	//Show a Category info
	public function show ($id)
	{
		$data = Categorys::find($id);
		return response()->json($data);
	}

	//Delete a category form storage
	public function destory (Request $request)
	{
		if (empty($request->ids)) {
			return response()->json(['status' => 'error', 'info' => 'ID不能为空']);
		}
		$result = Categorys::whereIn('id', $request->ids)->delete();
		return response()->json(['status' => !$result ? 'error' : 'success']);
	}
}