<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\ExploreModel;
use App\Models\LinkModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class LinkController extends \MainController
{
    public function index() 
    {
        $uid    = Base::getUid();
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $links  = LinkModel::getLinksAll($uid['id'], $page);
        
        $data = [
            'h1'            => lang('domains-title'),  
            'canonical'     => '/domains',
            'sheet'         => 'domains',
            'meta_title'    => lang('domains-title') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('domains-desc'), 
        ];
        
        return view(PR_VIEW_DIR . '/link/index', ['data' => $data, 'uid' => $uid, 'links' => $links]);
    }
    
    // Выборка по домену
    public function domain() 
    {
        $domain     = \Request::get('domain');
        $uid        = Base::getUid();
        
        $post       = LinkModel::getLinkUrl($domain, $uid['id']); 
        Base::PageError404($post);
        
        $result = Array();
        foreach ($post as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $result[$ind]                   = $row;
         
        }
        
        $link       = LinkModel::getLink($domain, $uid['id']);
        $domains    = LinkModel::getLinksTop($domain); 
        
        $meta_title = lang('Domain') . ': ' . $domain .' | '. Config::get(Config::PARAM_NAME);
        $meta_desc = lang('domain-desc') . ': ' . $domain .' '. Config::get(Config::PARAM_HOME_TITLE);
        
        $data = [
            'h1'            => lang('Domain') . ': ' . $domain,  
            'canonical'     => '/' . $domain,
            'sheet'         => 'domain',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc, 
        ];
        
        return view(PR_VIEW_DIR . '/link/domain', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'domains' => $domains, 'link' => $link]);
    }

    // Изменение
    public static function setLinkEdit($domain_id, $link_url, $link_title, $link_content)
    {
        XD::update(['links'])->set(['link_url'], '=', $link_url, ',', 
            ['link_title'], '=', $link_title, ',',
            ['link_content'], '=', $link_content)->where(['link_id'], '=', $domain_id)->run(); 
        return true;  
    }

    // Получим Favicon
    public static function getFavicon($url)
    {
        $url = str_replace("https://", '', $url);
        return "https://www.google.com/s2/favicons?domain=".$url;
    }
    
    // Запишем Favicon
    public function favicon()
    {
        $link_id    = \Request::getPostInt('id');
        $uid        = Base::getUid();

        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $link = LinkModel::getLinkId($link_id);
        
        if (!$link) {
            return false;
        }
        
        $puth = HLEB_PUBLIC_DIR. '/uploads/favicons/' . $link["link_id"] . '.png';
        $dirF = HLEB_PUBLIC_DIR. '/uploads/favicons/';

        if (!file_exists($puth)){  
            $urls = self::getFavicon($link['link_url_domain']);       
            copy($urls, $puth); 
        } 
        
        return true;
    }

}
