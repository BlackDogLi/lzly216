<?php
/**
 * 图片位置管理
 * User: liyu
 * Date: 2018/10/5
 * Time: 17:40
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\ImagePosition;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ImagePositionController extends Controller
{
    private $error;

    /**
     * @Desc 图片位置列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        $data = ImagePosition::where('deleted_at', '=', null)->get();
        return response()->json($data);
    }

    /**
     * @Desc 创建一个图片位置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //数据校验
        $this->validate($request,[
            'img_pname' => 'required|string',
            'img_flag'  => 'required|flag',
            'img_width' =>  'required|integer',
            'img_height' =>  'required|integer',
            'img_size' =>  'required|integer',
        ]);
        $imagePosition = new ImagePosition();
        $result = self::transform($imagePosition, $request);
        if (!$result) {
            $data['status'] = -1;
            $data['error'] = $this->error;
            $data['msg'] = '添加失败';
        } else {
            $data['status'] = 200;
            $data['msg'] = '添加成功';
        }
        return response()->json($data);
    }

    /**
     * @Desc 更新数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request)
    {
        if (empty($request->id)) {
            return response()->json(array('status' => -1, 'msg' => 'Id不能为空'));
        }

        $imagePosition =ImagePosition::updateOrCreate(['id' => $request->id]);
        $result = self::transform($imagePosition, $request);
        if (!$result) {
            $data['status'] = -1;
            $data['error'] = $this->error;
            $data['msg'] = '修改失败';
        } else {
            $data['status'] = 200;
            $data['msg'] = '修改成功';
        }
        return response()->json($data);
    }

    /**
     * @Desc 编辑获取数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($id)
    {
        $result = ImagePosition::find($id);
        if (empty($result)) {
            $data = array('status' => -1, 'data' => null);
        } else {
            $data = array('status' => 200, 'data' => $result);
        }
        return response()->json($data);
    }

    /**
     * @Desc 删除记录,支持批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy (Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(array('status' => -1, 'msg' => 'ID不能为空'));
        }
        $result = ImagePosition::whereIn('id', $request->ids)->delete();
        if ($result) {
            $data = array('status' => 200, 'msg' => '删除成功');
        } else {
            $data = array('status' => -1, 'msg' => '删除失败');
        }
        return response()->json($data);
    }
    /**
     * @param ImagePosition $imagePosition
     * @param Request $request
     * @return ImagePosition|null
     */
    private function transform (ImagePosition $imagePosition, Request $request)
    {
        $imagePosition->img_pname = $request->img_pname;
        $imagePosition->img_flag = $request->img_flag;
        $imagePosition->img_width = $request->img_width;
        $imagePosition->img_height = $request->img_height;
        $imagePosition->img_size = $request->img_size;
        $imagePosition->img_status = $request->img_status == true ? 1 : 0;
        try {
            $imagePosition->save();
            return $imagePosition;
        } catch (QueryException $e) {
            $this->error = $e;
            return null;
        }
    }
}