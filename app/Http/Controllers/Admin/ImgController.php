<?php
/**
 * 图片管理控制器.
 * User: ly
 * Date: 2018/11/12
 * Time: 15:43
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Img;


class ImgController extends Controller
{

    public function index ()
    {
        $result = Img::where('deleted_at', '=', null)->get();
        foreach ($result as $item) {
            $item->positions;
        }
        //dd($result);
        return response()->json($result);
    }

}