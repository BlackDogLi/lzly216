<?php
/**
 * 评论控制器
 * User: ly
 * Date: 2018/10/24
 * Time: 10:15
 */

namespace App\Http\Controllers\Home;

use App\Models\CommentsReplays;
use Illuminate\Http\Request;
use App\Models\Comments;
use Cookie;
//use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class CommentController extends Controller
{
    protected $error;

    /** 评论
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create (Request $request)
    {
        $data = array('status' => -1);
        $this->validate($request, [
            'comment' => 'required'
        ]);

        if ($request->main_id > 0) {
            //非主评论
            $commentsReplays = new CommentsReplays();
            $commentsReplays = self::commentsReplyTransform($commentsReplays, $request);

        } else {
            //主评论
            $comments = new Comments();
            $comments = self::transform($comments, $request);
            if (!$comments) {
                $data['error'] = $this->error;
            } else {
                $data['status'] = 200;
                $data['msg'] = '回复成功';
            }
        }

        return response()->json($data);
    }

    /**
     * @Desc: 评论主楼表
     * @param Comments $comments
     * @param Request $request
     * @return Comments|null
     */
    private function transform(Comments $comments, Request $request)
    {
        $comments->articles_id = $request->articles_id;
        $comments->main_id = $request->main_id > 0 ? $request->main_id : 0;
        $comments->nickname = $this->getUserName($request);
        $comments->username = $request->username;
        $comments->email = $request->email;
        $comments->uid = 0;
        $comments->reply_uid = 0;
        $comments->status = 1;
        $comments->content = addslashes(htmlspecialchars($request->comment));
        $comments->ipaddress = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();

        try {
            $comments->save();
            return $comments;
        } catch (QueryException $exception) {
            $this->error = $exception;
            return null;
        }

    }

    /**
     * @Desc:评论回复表
     * @param CommentsReplays $commentsReplays
     * @param Request $request
     * @return CommentsReplays|bool
     */
    private function commentsReplyTransform (CommentsReplays $commentsReplays, Request $request)
    {
        $commentsReplays->comments_id = $request->main_id;
        $commentsReplays->content = $request->comment;
        $commentsReplays->username = $this->getUserName($request);
        $commentsReplays->status = 1;
        $commentsReplays->ipaddress = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();
        dd($commentsReplays);
        try {
            $commentsReplays->save();
            return $commentsReplays;

        } catch (QueryException $exception) {
            $this->error = $exception;
            return false;
        }

    }

    /**
     * @Desc: 处理用户名字
     * @param Request $request
     * @return \Illuminate\Cookie\CookieJar|mixed|string|\Symfony\Component\HttpFoundation\Cookie
     */
    private function getUserName (Request $request)
    {
        $result = '';
        if (!empty(session('userName'))) {
            $result = session('userName');
        } else {
            if ($request->nickstatus  == false && !empty($request->username)) {
                $result = $request->username;
            } else {
                $result = 'UU' . random_int(10000,99999);
                session(['userName'=> $result]);
            }
        }
        return $result;
    }
}