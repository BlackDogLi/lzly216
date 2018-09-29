<?php
/**
 * User: Ly
 * Date: 2018/1/30
 * Time: 15:01
 */
namespace App\Factorymethod\Factory;

use Illuminate\Http\Request;
use App\Models\Categorys;
use Naux\AutoCorrect;
use App\Factorymethod\Factory\Product;
use App\Factorymethod\Interfaces\FactoryInterface;

class CategorysFactory implements Product
{
	public function create (FactoryInterface $observer, Request $request)
	{
		$category = new Categorys();
		$category = $this->transform($category, $request)->save();
		if (!$category) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($category);
	}
	public function update (FactoryInterface $observer, Request $request)
	{
		if (empty($request->id)) {
			$observer->creatorFail('ID不能为空');
		}
		$category = Categorys::updateOrCreate(['id' => $request->id]);
		$category = $this->transform($category, $request);
		if (!$category) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($category);
	}
	public function transform (Categorys $categorys, Request $request)
	{
        $correct = new AutoCorrect();
		$categorys->category_name = $correct->convert($request->category_name);
		$categorys->category_flag = $request->category_flag;
		$categorys->category_description = $correct->convert($request->category_description);
		$category_parent = $request->category_parent;
		$categorys->category_parent = is_array($category_parent) ? end($category_parent) : intval($category_parent);
		$categorys->category_ids = empty($category_parent)? '0' : '0,'.implode(',',$category_parent);
		$categorys->ipaddress = $request->ip();
		$categorys->save();
		return $categorys;
	}
}