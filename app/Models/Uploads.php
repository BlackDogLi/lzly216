<?php
/**
 * 文件上传类
 * User: Ly
 * Date: 2018/3/14
 * Time: 14:51
 */
namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
class Uploads
{
	protected $client_ip = '127.0.0.1';
	protected $file = array();

	public function fileInfo ($file) {
		$file['originalName'] = $file->getClientOriginalName(); //文件原名
		$file['ext'] = $file->getClientOriginalExtension(); //扩展名
		$file['realPath'] = $file->getRealPath();   //临时文件的绝对路径
		$file['type'] = $file->getClientMimeType(); // 文件类型image/jpeg
		return $file;
	}

}