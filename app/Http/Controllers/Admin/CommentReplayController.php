<?php
/**
 * 评论回复控制器.
 * User: liyu
 * Date: 2019/1/29
 * Time: 18:12
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\CommentsReplays;
use Illuminate\Http\Request;

class CommentReplayController extends Controller
{

    /**
     * @Desc 存储评论回复
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request)
    {
        $commentReplay = new CommentsReplays();
        $commentReplay->comments_id = $request->commentId;
        $commentReplay->content = $request->contents;
        $commentReplay->status = 1;
        $commentReplay->ipaddress = $request->getClientIp();
        $res = $commentReplay->save();
        if ($res) {
            return response()->json(['status' => 200, 'msg' => '回复成功']);
        } else {
            return response()->json(['status' => -1, 'msg' => '回复失败！']);
        }

    }

}