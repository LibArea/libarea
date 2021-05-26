<?php

namespace App\Models;
use XdORM\XD;

class SitemapModel extends \MainModel
{
    // Все посты для карты
    public static function getPostsSitemap()
    {
        return XD::select(['post_id', 'post_slug', 'post_is_delete'])->from(['posts'])->where(['post_is_delete'], '!=', 1)->getSelect();
    } 

    // Все пространства для карты
    public static function getSpacesSitemap()
    {
        return XD::select(['space_slug', 'space_is_delete'])->from(['space'])->where(['space_is_delete'], '!=', 1)->getSelect();
    } 
    
}
