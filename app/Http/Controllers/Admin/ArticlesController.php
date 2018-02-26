<?php
/**
 * 文章
 * User: Ly
 * Date: 2018/2/6
 * Time: 14:23
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Articles;
use App\Factorymethod\Factory\ArticlesFactory;
use App\Factorymethod\Interfaces\FactoryInterface;

class ArticlesController extends Controller implements FactoryInterface
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

	//listing
	public function index (Request $request)
	{
		var_dump('111');
		die;
		$rows = intval($request->rows) > 0 ? $request->rows : 20;
		$listData = Articles::OfCategory($request->category_id)->OfTitle($request->q)->paginate($rows);
		$listData = $this->transform($listData);
		return response()->json($listData);
	}

	//Store a article info
	public function store (Request $request)
	{
		$this->validate($request, [
			'title' => 'required',
			'flag' => 'required|flag',
			'category_id' => 'required',
			'markdown' => 'required'
		]);
		app(ArticlesFactory::class)->create($this, $request);
		return response()->json($this->_reponse);
	}

	//Update a article info
	public function update (Request $request)
	{
		$this->validate($request, [
			'title' => 'required',
			'flag' => 'required|flag',
			'category_id' => 'required',
			'markdown' => 'required'
		]);
		app(ArticlesFactory::class)->update($this, $request);
		return response()->json($this->_response);
	}

	//show a article info
	public function show ($id)
	{
		$data = Articles::find($id);
		$data['tags'] = $data->tags;
		return response()->json($data);
	}

	//Remove a article from storage
	public function destory (Request $request)
	{
		$this->validate($request, [
			'ids' => 'required'
		]);
		$result = Articles::whereIn('id', $request->ids)->delete();
		return response()->json(['status' => !$result ? 'error' : 'success']);
	}

	//data transform format
	public function transform ($data)
	{
		foreach ($data as $key => $item)
		{
			$item->tags;
			$item->categories;
		}
		return $data;
	}
}