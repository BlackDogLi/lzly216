<?php
/**
 * 标签
 * User: Ly
 * Date: 2018/1/31
 * Time: 13:52
 */
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tags extends Model
{
	protected $fillable = ['tags_name', 'tags_flag'];

	public static function createArticlesTags ($article_id, $tag) {
		$articles_tags = [];
		foreach ($tags as $k => $tag) {
			$tagsInfo = self::firstOrCreate(['tags_name' => $tag]);
			$tagsInfo->tags_name = $tag;
			$tagsInfo->tags_flag = urlencode($tag);
			$tagsInfo->save();
			$articles_tags[$k] = [
				'articles_id' => $article_id,
				'tags_id' => $tagsInfo->id,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			];
		}
		ArticlesTags::where('articles_id', $article_id)->delete();
		DB::table('articles_tags')->insert($articles_tags);

	}

	public function articles () {
		return $this->belongsToMany(Articles::class);
	}
}