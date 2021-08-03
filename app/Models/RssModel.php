<?php

namespace App\Models;
use XdORM\XD;

class RssModel extends \MainModel
{
    // Все посты для Sitemap
    public static function getPostsSitemap()
    {
        return XD::select(['post_id', 'post_slug', 'post_tl', 'post_is_deleted'])->from(['posts'])
                    ->where(['post_is_deleted'], '!=', 1)
                    ->and(['post_tl'], '=', 0)->getSelect();
    } 

    // Все пространства для Sitemap
    public static function getSpacesSitemap()
    {
        return XD::select(['space_slug', 'space_is_delete'])->from(['spaces'])
                    ->where(['space_is_delete'], '!=', 1)->getSelect();
    } 
    
    // Посты по id пространства для rss
    public static function getPostsFeed($space_id)
    {
        return XD::select('*')->from(['posts'])
                   ->where(['post_space_id'], '=', $space_id)
                   ->and(['post_is_deleted'], '!=', 1)
                   ->and(['post_tl'], '=', 0)
                   ->and(['post_content_img'], '!=', '')->orderBy(['post_id'])->desc()->getSelect();
    } 
    
    // Информация по самому пространству для rss
    public static function getSpaceId($space_id)
    {
        return XD::select('*')->from(['spaces'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }
}
