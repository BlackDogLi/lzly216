<?php
/**
 * 前台页面公用数据
 * User: ly
 * Date: 2018/5/8
 * Time: 15:51
 */
namespace App\Http\ViewComposers;

use App\Models\Categorys;
use Illuminate\Contracts\View\View;
use App\Models\Navications;

class Common
{
    //private $navigation;

    public function __construct ()
    {
    }

    public function compose (View $view)
    {
        $navigation = Navications::where('isShow', '=', 1)->orderBy('sort', 'asc')->get();
        $categorys = Categorys::select('id' ,'category_name', 'category_parent', 'category_flag','category_description')->get();
        $cates = getTree($categorys);
        //$cates = categoryTree();

        //获取下拉菜单
        foreach ($navigation as $key => $value) {
            $value['children'] = $cates[$key]['children'];
            $nav[] = $value;
        }
        $view->with( 'nav', $nav);
    }
}