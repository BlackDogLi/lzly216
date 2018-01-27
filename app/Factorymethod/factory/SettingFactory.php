<?php
/**
 * 网站配置项
 * User: Ly
 * Date: 2018/1/19
 * Time: 16:53
 */
namespace App\Factorymethod\Factory;

use Illuminate\Http\Request;
use App\Models\Setting;
use Naux\AutoCorrect;
use App\Factorymethod\Factory\Product;
use App\Factorymethod\Interfaces\FactoryInterface;

class SettingFactory implements Product
{
	// 插入数据
	public function create (FactoryInterface $observer, Request $request)
	{
		$sets = new Setting();
		$sets = $this->transform($sets, $request);

		if (!$sets) {
			$observer->creatorFail('error');
		}
		$observer->creatorSuccess($sets);
	}

	// 更新数据
	public function update (FactoryInterface $observer, Request $request)
	{
		if (empty($request->id)) {
			$observer->creatorFail('ID 不能为空');
		}
		$sets = Setting::firstOrCreate(['set_name' => $request->set_name]);
		$sets = $this->transform($sets, $request);

		if (!$sets) {
			$observer->creatorFail('error');
		}

		$observer->creatorSuccess($sets);
	}
	public function transform (Setting $sets, Request $request)
	{
		$autoCorrect = new AutoCorrect();
		$sets->set_title = $autoCorrect->convert($request->set_title);
		if ($sets->set_status == 'base') {

		}
		$sets->set_name = $request->set_name;
		$sets->set_value = $request->set_value;
		$sets->set_group = $request->set_group;
		$sets->set_remark = $request->set_remark;
		$sets->save();
		return $sets;
	}
}
