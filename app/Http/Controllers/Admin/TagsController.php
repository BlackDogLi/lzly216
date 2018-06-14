<?php
/**
 * 标签管理控制器.
 * User: ly
 * Date: 2018/6/12
 * Time: 13:43
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Factorymethod\Interfaces\FactoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Tags;

class TagsController extends Controller implements FactoryInterface
{
    private $_response = [];

    public function creatorFail ($error)
    {
        $this->_response = ['status' => 'error', 'info' => $error];
    }
    public function creatorSuccess ($model)
    {
        $this->_response = ['status' =>'success', 'info' => '操作成功'];
    }

    /**
     * @Desc: Tags list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $listData = Tags::paginate($rows);
        $listData = $this->transform($listData);
        return response()->json($listData);
    }

    /**
     * @Desc: Store new tag resource
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request)
    {
        $this->validate($request, [
            'tags_name' => 'required',
            'tags_flag' => 'required|flag'
        ]);
        app(\App\Factorymethod\Factory\TagsFactory::class)->create($this,$request);
        return response()->json($this->_response);
    }

    /**
     * @Desc: Update a tag resource
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request)
    {
        $this->validate($request, [
            'tags_name' => 'required',
            'tags_flag' => 'required|flag'
        ]);
        app(\App\Factorymethod\Factory\TagsFactory::class)->update($this, $request);
        return response()->json($this->_response);
    }

    public function destroy (Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'info' => 'ID not null']);
        }
        $result = Tags::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

    /**
     * @Desc: show a specify tag resource
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($id)
    {
        $data = Tags::find($id);
        return response()->json($data);
    }

    /**
     * @Desc: data format
     * @param $data
     * @return mixed
     */
    public function transform ($data)
    {
        return $data;
    }

}