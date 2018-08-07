<?php
/**
 * 文章
 * User: Ly
 * Date: 2018/1/30
 * Time: 17:49
 */
namespace App\Factorymethod\Factory;

use App\Thirdservice\BaiduPush;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\Tags;
use Illuminate\Support\Facades\Auth;
use Naux\AutoCorrect;
use App\Factorymethod\Factory\Product;
use App\Factorymethod\Interfaces\FactoryInterface;

class ArticlesFactory implements Product
{
	protected $_error = 'error';

	public function create (FactoryInterface $observer, Request $request)
	{
		$articles = new Articles();
		$articles = $this->transform($articles, $request);
		if (!$articles) {
			$observer->creatorFail($this->_error);
		}
		//添加标签
		Tags::createArticlesTags($articles->id, $request->tags);

		//百度推送
        (new BaiduPush(route('article',[$articles->flag])))->pushUrl();

		$observer->creatorSuccess($articles);
	}

	public function update (FactoryInterface $observer, Request $request)
	{
		if (empty($request->id)) {
			$observer->creatorFail('ID不能为空');
		}
		$articles = Articles::updateOrCreate(['id' => $request->id]);
		//标签更新
		Tags::createArticlesTags($articles->id, $request->tags);
		$articles = $this->transform($articles, $request);
		if (!$articles) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($articles);
	}

	public function transform (Articles $articles, Request $request)
	{
		$correct = new AutoCorrect();
		$articles->title = $correct->convert($request->title);
		$articles->flag = strtolower($request->flag);
		$articles->thumb = $request->thumb;
		$articles->category_id = $request->category_id;
		$articles->user_id = Auth('admin')->id();
		$articles->content = (new \Parsedown())->text($request->markdown);
		$articles->markdown = $request->markdown;
		$articles->ipaddress = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();
		try {
			$articles->save();
			return $articles;
		} catch (QueryException $exception) {
			if ($exception->errorInfo[1] == 1062) {
				$this->_error = '文章插入失败,flag重复了';
			}
			return null;
		}
	}
}