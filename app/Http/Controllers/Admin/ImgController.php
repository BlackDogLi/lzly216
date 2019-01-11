<?php
/**
 * 图片管理控制器.
 * User: ly
 * Date: 2018/11/12
 * Time: 15:43
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\ImagePosition;
use App\Models\Img;
use Illuminate\Http\Request;


class ImgController extends Controller
{

    /**
     * @Desc 图片列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $resImg = Img::OfPosition($request->position_id)->where('deleted_at', '=', null)->orderBy('created_at','Desc')->paginate($rows);
        foreach ($resImg as $item) {
            $item->positions;
        }
        $resPosition = ImagePosition::where('deleted_at', '=', null)->get();
        return response()->json(array('img' => $resImg, 'imgposition' => $resPosition));
    }

    

}