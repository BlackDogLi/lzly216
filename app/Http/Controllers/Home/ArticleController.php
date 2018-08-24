<?php
/**
 * 采菊园控制器.
 * User: ly
 * Date: 2018/7/16
 * Time: 11:01
 */

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use App\Models\Articles;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use App\Events\ArticleView;


class ArticleController extends Controller
{
    public function index ()
    {
        return view('home.caijuyuan.index');
    }

    /**
     * 获取文章列表
     * @param $id   分类Id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleList ($id)
    {
        $key = 'articleListByCategory-' . $id;
        if (Cache::has($key)) {
            $artilceList = Cache::get($key);
        } else {
            $artilceList = $this->articleListByCategory($id);
            Cache::put($key,$artilceList, 10);
        }
        return view('home.article.articleList', ['articleList' => $artilceList]);
    }

    /**
     * 文章详情
     * @param $flag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail ($flag)
    {
        $key = 'ArticleDetail-' . $flag;    //存取key
        //获取文章详情
        if(Cache::has($key)) {
            $articleDetail = Cache::get($key);
        } else {
            $articleDetail = Articles::select('id', 'title', 'flag', 'content', 'views')->where('flag', '=', $flag)->first();
            Cache::put($key, $articleDetail, 10);
        }
        Event::fire(new ArticleView($articleDetail));
        return view('home.articledetail', ['articleDetail' => $articleDetail]);
    }
    /**
     * 根据分类ID获取文章列表
     * @param int $category_id
     * @return \Illuminate\Support\Collection
     */
    private function articleListByCategory ($category_id = 0)
    {
        return Articles::select('id', 'title', 'flag', 'thumb', 'content')->OfCategory('', $category_id, true)->orderBy('id', 'DESC')->get();
    }
}