<?php
/**
 * 文件上传
 * User: Ly
 * Date: 2018/3/9
 * Time: 15:27
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UploadsController extends Controller
{
	public function store (Request $request)
	{
		if ($request->isMethod('post'))
		{
			$file = $request->file('file');

			//文件是否上传成功
			if ($file->isValid())
			{
				//获取文件相关信息
				$originalName = $file->getClientOriginalName(); //文件原名
				$ext = $file->getClientOriginalExtension(); //扩展名
				$realPath = $file->getRealPath();   //临时文件的绝对路径
				$type = $file->getClientMimeType(); // 文件类型image/jpeg

				//上传文件
				$filename = date('Y-m-d') . '-' . uniqid() . '.' . $ext;
				//使用我们新建的uploads本地存错空间(目录)
				$url1 = public_path('uploads/'.$filename);
				$bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
				//裁剪图片
				$img = Image::make($url1);
				$img->resize(200,150);
				$img->save();
				//var_dump(storage_path('uploads'));
				$url = '/uploads/' . $filename;
				//$url = Storage::url($filename); //存储路径
				if ($bool){
					return response()->json(['status' => '200', 'filename' => $filename, 'url' => $url]);
				} else {
					return response()->json(['status' => '400']);
				}
			}

		}
		return response()->json(['status' => '400']);
	}
}