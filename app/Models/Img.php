<?php
/**
 * 图片模型类.
 * User: ly
 * Date: 2018/11/12
 * Time: 16:35
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Img extends Model
{
    use SoftDeletes;

    //应该被调整为日期的属性
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public function positions ()
    {
        return $this->belongsTo(ImagePosition::class, 'position_ids');
    }

}