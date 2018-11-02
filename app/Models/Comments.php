<?php
/**
 * 文章评论
 * User: ly
 * Date: 2018/10/24
 * Time: 14:43
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    use SoftDeletes;


}