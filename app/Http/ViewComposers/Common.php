<?php
/**
 * 前台页面公用数据
 * User: ly
 * Date: 2018/5/8
 * Time: 15:51
 */
namespace App\Http\ViewComposers;

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
        $view->with( 'nav', $navigation);
    }
}