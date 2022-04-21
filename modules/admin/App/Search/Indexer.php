<?php

namespace Modules\Admin\App\Search;

use Modules\Search\App\Engine;
use Modules\Admin\App\Search\Actions;
use Modules\Admin\App\Models\IndexerModel;
use Html, Content;

class Indexer
{
    private $engine;
    
    private $actions;

    public function __construct()
    {
        $this->engine  = new Engine();
        $this->actions = new Actions();
    }

    public function indexerAll()
    {
        self::indexerPost();
        self::indexerItem();
        redirect(getUrlByName('admin.search'));
    }

    public function indexerPost()
    {
        $posts = IndexerModel::getPosts();
        foreach ($posts as $post) {
            $content = Html::fragment(Content::text($post['post_content'], 'line'), 600);
            $title = $this->filtration($post['post_title']);
            $arr = [
                "id"        => $post['post_id'],
                "type"      => "example-url",
                "title"     => $title,
                "content"   => $content,
                "domain"    => '',
                "url"       => '',
                "cat"       => ["post"],
            ];

            $this->actions->update($arr);
        }
    }

    public function indexerItem()
    {
        $items = IndexerModel::getItems();
        foreach ($items as $item) {

            $title = $this->filtration($item['item_title']);
            $arr = [
                "id"        => $item['item_id'],
                "type"      => "example-url",
                "title"     => $title,
                "content"   => $item['item_content'],
                "domain"    => $item['item_domain'],
                "url"       => $item['item_url'],
                "cat"       => ["website"],
            ];

            $this->actions->update($arr);
        }
    }

    public function filtration($title)
    {
        $chars = ['«', '»'];
        return str_replace($chars, '', $title);
    }

    public function clearCache()
    {
        $this->engine->getIndex()->clearCache();
        $this->engine->getIndex()->rebuild();
    }
}
