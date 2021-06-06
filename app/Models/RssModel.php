<?php

namespace App\Models;
use XdORM\XD;

class RssModel extends \MainModel
{
    // Все посты для Sitemap
    public static function getPostsSitemap()
    {
        return XD::select(['post_id', 'post_slug', 'post_is_delete'])->from(['posts'])->where(['post_is_delete'], '!=', 1)->getSelect();
    } 

    // Все пространства для Sitemap
    public static function getSpacesSitemap()
    {
        return XD::select(['space_slug', 'space_is_delete'])->from(['space'])->where(['space_is_delete'], '!=', 1)->getSelect();
    } 
    
    // Посты по id пространства для rss
    public static function getPostsFeed($space_id)
    {
        return XD::select(['post_id', 'post_slug', 'post_title', 'post_date', 'post_content', 'post_content_img', 'post_is_delete'])->from(['posts'])->where(['post_is_delete'], '!=', 1)->and(['post_content_img'], '!=', '')->and(['post_space_id'], '=', $space_id)->orderBy(['post_id'])->desc()->getSelect();
    } 
    
    // Информация по самому пространству для rss
    public static function getSpaceId($space_id)
    {
        return XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }
}
