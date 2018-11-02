<?php
/**
 * 文章评论.
 * User: ly
 * Date: 2018/10/29
 * Time: 16:12
 */

namespace App\Factorymethod\Factory;


use App\Factorymethod\Interfaces\FactoryInterface;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Models\Comments;

class CommentsFactory implements Product
{
    protected $error;
    public function create (FactoryInterface $observer, Request $request)
    {
        $comments = new Comments();
        $comments = $this->transform($comments, $request);
        if (!$comments) {
            $observer->creatorFail($this->error);
        }
        $observer->creatorSuccess($comments);
    }

    public function update (FactoryInterface $observer, Request $request)
    {
        // TODO: Implement update() method.
    }

    public function transform (Comments $comments, Request $request)
    {
        $comments->articles_id = $request->articles_id;
        $comments->main_id = $request->main_id > 0 ? $request->main_id : 0;
        $comments->username = empty($request->username) ? '' : $request->username;
        $comments->uid = empty(session('userId')) ? 0 : session('userId');
        $comments->reply_uid = 0;
        $comments->status = 1;
        $comments->content = $request->comment;
        $comments->ipaddress = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();
        try {
            $comments->save();
            return $comments;

        } catch (QueryException $exception) {
            $this->error = $exception;
            return null;
        }

    }

}