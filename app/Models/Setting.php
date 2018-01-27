<?php
/**
 * 设置项
 * User: Ly
 * Date: 2018/1/19
 * Time: 9:56
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $table = 'setting';
	protected $fillable = ['set_name', 'set_value'];


	//非基础数据
	public function scopeNotbase ($query) {
		return $query->where('set_status', '!=', 'base');
	}

	//非隐藏数据
	public function scopeNohidden ($query) {
		return $query->where('set_status', '!=', 'hidden');
	}
}