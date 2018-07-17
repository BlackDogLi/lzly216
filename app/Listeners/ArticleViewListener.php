<?php

namespace App\Listeners;

use App\Events\ArticleView;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Session\Store;

class ArticleViewListener
{
    protected $session;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle the event.
     *
     * @param  ArticleView  $event
     * @return void
     */
    public function handle(ArticleView $event)
    {
        $article = $event->article;
        if (!$this->hasViewedArticle ($article)) {
            //保存到数据库
            $article->views = $article->views + 1;
            $article->save();
            //看过之后保存到缓存
            $this->storeViewArticle($article);
        }

    }

    protected function hasViewedArticle ($article)
    {
        return array_key_exists($article->id, $this->getViewArticle());
    }

    protected function getViewArticle ()
    {
        return $this->session->get('View_Article', []);
    }

    protected function storeViewArticle ($article)
    {
        $key = 'View_Article.' . $article->id;
        $this->session->put($key, time());
    }
}
