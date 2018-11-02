<?php
/**
 * 评论控制器
 * User: ly
 * Date: 2018/10/24
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * @Desc: comments list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $commentList = Comments::select('id', 'username', 'content', 'ipaddress', 'email', 'nickname', 'created_at', 'status')
                        ->where('deleted_at', '=', null)->orderBy('created_at', 'Desc')->paginate($rows);
        return response()->json($commentList);
    }

    /**
     * @Desc: update the status of comments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request)
    {
        $result = Comments::select('id', '=', $request->id)->update(array('status' => $request->status));
        if ($result) {
            $data = array('status' => 200, 'msg' => '修改成功', 'data' => $result);
        } else {
            $data = array('status' => -1, 'msg' => '修改失败', 'data' => $result);
        }
        return response()->json($data);
    }

    /**
     * @Desc: delete comments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy (Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $comment = Comments::where('id', '=', $request->id)->first();
        if ($comment->delete()) {
            $data = array('status' => 200, 'msg' => '删除成功');
        } else {
            $data = array('status' => -1, 'msg' => '删除失败');
        }
        return response()->json($data);
    }

}