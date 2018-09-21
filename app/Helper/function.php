<?php
/**
 * User: Ly
 * Date: 2018/3/23
 * Time: 15:23
 */
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


// 判断路由
if (!function_exists('current_is'))
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
if (!function_exists('bloginfo'))
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

//记录日志到指定文件
if (!function_exists('recordLog'))
{
    function recordLog ($name = '', $path = '', $content = '')
    {
        $name = empty($name) ? 'log' : $name;
        $logPath = date('Y-m-d', time()).'-log.txt';
        $path = empty($path) ? storage_path('logs/'. $logPath) : $path;
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler($path, Logger::INFO));
        $log->info($content);
    }
}

//生成分类树
if (!function_exists('getTree'))
{
    function getTree($data, $parentId = 0) {
        $res = array();
        foreach ($data as $value) {
            if ($value['category_parent'] == $parentId) {
                $value['children'] = getTree($data, $value['id']);
                if (empty($value['children'])) unset($value['children']);
                $res[] = $value;
            }
        }
        return $res;
    }

}

/**
 * 查询父类
 */
if (!function_exists('parentIds'))
{
    function parentIds ($id)
    {
        $result = array();
        $parentId = '';
        $rows = \App\Models\Categorys::where('id','=',$id)->first();

        if ($rows) {
            $parentId .= $rows['category_parent'];
            $result = parentIds($rows['category_parent']);
            if ($result) {
                $parentId .= ','.$result;
            }
        }
        return $parentId;
    }
}
