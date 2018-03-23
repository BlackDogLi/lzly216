<?php
/**
 * User: Ly
 * Date: 2018/3/23
 * Time: 15:23
 */
// 判断路由
if (!function_exists("current_is"))
{
	function current_is ($name)
	{
		$is = false;
		$route = \Illuminate\Support\Facades\Route::current();
		if ($route->getName() == $name) {
			$is = true;
		}
		return $is;
	}

}

//读取动态配置
if (!function_exists("bloginfo"))
{
	function bloginfo ($name, $clear = false)
	{
		$options = cache('options');
		if (empty($options) || $clear)
		{
			cache()->forget('options');
			$expiresAt = \Carbon\Carbon::now()->addMinutes(1440);
			$optionsList = \App\Models\Setting::orderBy('id')->select('set_name', 'set_value')->get()->toArray();
			foreach ($optionsList as $key => $option) {
				$options[strtolower($option['set_name'])] = $option['set_value'];
			}
			cache(['options' => $options], $expiresAt);
		}
		return isset($options[$name]) ? $options[$name] : '';
	}
}