<?php

namespace Modules\Admin\App;

use Modules\Search\App\Engine;
use Modules\Admin\App\Models\IndexerModel;

class Indexer
{
    private $engine;

    public function __construct()
    {
        $this->engine  = new Engine();
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

            $title = $this->filtration($post['post_title']);
            $arr = [
                "id"        => $post['post_id'],
                "type"      => "example-url",
                "title"     => $title,
                "content"   => $post['post_content'],
                "domain"    => '',
                "url"       => '',
                "cat"       => ["post"],
            ];

            $this->engine->update($arr);
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
            
            $this->engine->update($arr);
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
