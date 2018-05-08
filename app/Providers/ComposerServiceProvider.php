<?php
/**
 * 视图Composer,提供前台公用函数
 * User: ly
 * Date: 2018/5/8
 * Time: 16:12
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\Common;

class ComposerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        view()->composer('home.common.nav', Common::class);
    }

    public function register()
    {

    }
}