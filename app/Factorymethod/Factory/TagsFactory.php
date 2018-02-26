<?php
/**
 * 标签
 * User: Ly
 * Date: 2018/1/31
 * Time: 13:47
 */
namespace App\Factorymethod\Factory;

use Illuminate\Http\Request;
use App\Models\Tags;
use Naux\AutoCorrect;
use App\Factorymethod\Factory\Product;
use App\Factorymethod\Interfaces\FactoryInterface;

class TagsFactory implements Product
{
	public function create (FactoryInterface $observer, Request $request)
	{
		$tags = new Tags;
		$tags = $this->transform($tags, $request);
		if (!$tags) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($tags);
	}

	public function update (FactoryInterface $observer, Request $request)
	{
		if (empty($request->id)) {
			$observer->creatorFail('ID不能为空');
		}
		$tags = Tags::firstOrCreate(['tags_name' => $request->tags_name]);
		$tags = $this->transform($tags, $request);
		if (!$tags) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($tags);
	}

	public function transform (Tags $tags, Request $request)
	{
		$correct = new AutoCorrect();
		$tags->tags_name = $correct->convert($request->tags_name);
		$tags->tags_flag = urlencode(strtolower($request->tags_flag));
		$tags->save();
		return $tags;
	}
}