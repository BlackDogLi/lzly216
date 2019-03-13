<?php
/**
 * 文章分类
 * User: Ly
 * Date: 2018/1/30
 * Time: 15:04
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
	//白名单，里面包含字段值可以被修改  $guarded对应黑名单
	protected $fillable = ['category_name', 'category_flag'];


	public function scopeOfArticles ($query, $category_id, $status = false)
    {
        if (intval($category_id) > 0) {
            if ($status) {
                return $query->where('id', $category_id)->orwhere('category_parent', $category_id);
            } else {
                return $query->where('id', $category_id);
            }
        }
        return $query;
    }

	public function posts ()
	{
		return $this->hasMany(Articles::class, 'category_id');
	}

	public function childCategory() {
        return $this->hasMany(Categorys::class, 'category_parent','id');
    }

    public function allChildrenCategorys() {
	    return $this->childCategory()->with('allChildrenCategorys');
    }
}