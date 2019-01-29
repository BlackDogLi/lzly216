<?php
/**
 * 文件上传
 * User: Ly
 * Date: 2018/3/9
 * Time: 15:27
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ImagePosition;
use App\Models\Img;
use Illuminate\Http\Request;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;


class UploadsController extends Controller
{
    /**
     * @Desc 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function store (Request $request)
	{

        $positionInfo = self::getImgPositionInfo($request->pos);

		if ($request->isMethod('post')) {
		    $data = [];
		    $fileList = $request->fileList;
		    foreach ($fileList as $value) {
                //获取文件相关信息
                $originalName = $value->getClientOriginalName(); //文件原名
                $ext = $value->getClientOriginalExtension(); //扩展名
                $realPath = $value->getRealPath();   //临时文件的绝对路径
                $type = $value->getClientMimeType(); // 文件类型image/jpeg

                //校验图片大小以及尺寸
                $imgCheck = self::checkImage($realPath, $positionInfo);
                if (!empty($imgCheck)) {
                    return response()->json($imgCheck);
                    break;
                }

                //上传文件
                $imgUrl = self::saveImage($realPath, $ext);

                //图片裁剪
                Image::make(public_path($imgUrl))->resize($positionInfo['img_width'], $positionInfo['img_height'])->save(public_path($imgUrl));

                //记录图片路径以及位置信息
                if(self::insertImgData($imgUrl, $positionInfo['id'])) {
                    $data[] = $imgUrl;
                }
            }

            if (!empty($data)) {
                return response()->json(['status' => 200, 'msg' => '图片上传成功']);
            } else {
                return response()->json(['status' => -1, 'msg' => '上传失败,请重新上传']);
            }
		}
		return response()->json(['status' => '-1', 'msg' => '请求出错！']);
	}

    /**
     * @Desc 获取图片位置信息
     * @param int $id
     * @return mixed
     */
	private function getImgPositionInfo ($id = 0)
    {
        return ImagePosition::find($id,['id', 'img_width', 'img_height', 'img_size']);
    }

    /**
     * @Desc 保存图片内容
     * @param string $realPath
     * @param string $ext
     * @return string
     */
    private function saveImage ($realPath = '', $ext = 'jpeg')
    {
        $dirName = date('Y-m-d');   //目录名称
        $fileName = uniqid() . '.' . $ext;
        $url = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $fileName;
        $bool = Storage::disk('uploads')->put('image' . DIRECTORY_SEPARATOR . $dirName .DIRECTORY_SEPARATOR . $fileName, file_get_contents($realPath));
        if ($bool) {
            return $url;
        } else {
            return '';
        }
    }

    /**
     * @Desc 校验图片尺寸以及大小
     * @param string $path
     * @param array $positionInfo
     * @return array
     */
    private function checkImage ($path = '', $positionInfo = [])
    {
        $data = [];
        $img = Image::make($path);
        if ($img->width() != $positionInfo['img_width'] || $img->height() != $positionInfo['img_height']) {
            $data = array('status' => -1, 'msg' => '图片尺寸不符合要求');
        }

        if($img->filesize()/1000 > $positionInfo['img_size']) {
            $data = array('status' => -1, 'msg' => '图片超出限定大小');
        }
        return $data;
    }

    /**
     * @Desc 保存图片路径以及位置信息至数据库
     * @param string $imgUrl
     * @param int $positionId
     * @return bool
     */
    private function insertImgData ($imgUrl = '', $positionId = 0)
    {
        $img = new Img();
        $img->position_ids = $positionId;
        $img->img_path = $imgUrl;
        $img->status = 1;
        return $img->save();
    }

}