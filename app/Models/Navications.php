<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navications extends Model
{
    //
    //白名单，里面包含字段值可以被修改  $guarded对应黑名单
    protected $fillable = ['name', 'url', 'sort', 'isShow'];
}
