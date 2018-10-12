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

class ImagePositionController extends Controller
{

    public function index ()
    {
        $data = ImagePosition::where('img_status', '=', 1)->get();
        //var_dump(response()->json($data));exit();
        return response()->json($data);
    }

}