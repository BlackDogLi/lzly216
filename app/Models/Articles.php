<?php
/**
 * 文章
 * User: Ly
 * Date: 2018/1/30
 * Time: 16:18
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articles extends Model
{
	use SoftDeletes;

	//应该被调整为日期的属性
	protected $dates = ['deleted_at'];
	protected $fillable = ['title', 'flag'];

	public function scopeOfType ($query, $type) {
		return $query;
	}

	/*
	 * 注入分类查询的条件
	 * @param   $query
	 * @param   $category_id
	 * @return  mixed
	 */
	public function scopeOfCategory ($query, $category_id) {
		if (intval($category_id) > 0) {
			return $query->where('category_id', $category_id);
		}
		return $query;
	}

	/*
	 * 注入文章标题或者模糊条件查询
	 * @param   $query
	 * @param   $title
	 * @return  mixed
	 */
	public function scopeOfTitle ($query, $title) {
		if (!empty($title)) {
			return $query->where('title', 'like', '%' . $title . '%');
		}
		return $query;
	}

	//标签对应
	public function tags () {
		return $this->belongsToMany(Tags::class, 'article_tags', '');
	}

	//
	public function user () {
		return $this->belongsTo(AdminUsers::class);
	}
	//
	public function categories () {
		return $this->belongsTo(Categorys::class, 'category_id');
	}
}