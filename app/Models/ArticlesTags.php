<?php
/**
 * 文章标签关系
 * User: Ly
 * Date: 2018/1/31
 * Time: 14:50
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlesTags extends Model
{
	protected $fillable = ['article_id', 'tag_id'];
}