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

	//Category List
	public function index (Request $request)
	{
		$categorys = Categorys::select('id' ,'category_name', 'category_parent', 'category_flag','category_description')->get();
		$data = getTree($categorys);
		return response()->json($data);
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

		//$parentIds = parentIds($id);

		$category_ids = explode(',',substr($data['category_ids'],2));
        foreach ($category_ids as $value) {
            $parentIds[] = (int)$value;
        }
		$data['parents'] = $parentIds;

		return response()->json($data);
	}

	//Delete a category form storage
	public function destroy (Request $request)
	{

		if (empty($request->id)) {
			return response()->json(['status' => 'error', 'info' => 'ID不能为空']);
		}

        $result = Categorys::where('id', '=', $request->id)->delete();
		if (!$result) {
		    $data = array('status' => 'error' , 'msg' => '删除失败');
        } else {
            $data = array('status' => 'error' , 'msg' => '删除成功');
        }
		return response()->json($data);
	}
}