<?php
/**
 * 图片位置模型类.
 * User: liyu
 * Date: 2018/10/5
 * Time: 17:47
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ImagePosition extends Model
{
    //白名单，里面包含字段值可以被修改  $guarded对应黑名单
    protected $fillable = ['img_pname', 'img_flag', 'img_width', 'img_height', 'img_status', 'img_size'];

    public function imgs ()
    {
        return $this->hasMany(Img::class, 'position_ids');
    }

}