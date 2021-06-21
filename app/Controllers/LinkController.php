<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\ExploreModel;
use App\Models\LinkModel;
use Lori\Config;
use Lori\Base;

class LinkController extends \MainController
{
    public function index() 
    {
        $uid        = Base::getUid();
        $data = [
            'h1'            => lang('domains-title'),  
            'canonical'     => '/domains',
            'sheet'         => 'domains',
            'meta_title'    => lang('domains-title') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('domains-desc'), 
        ];
        
        return view(PR_VIEW_DIR . '/link/index', ['data' => $data, 'uid' => $uid]);
    }
    
    // Выборка по домену
    public function domain() 
    {
        $domain     = \Request::get('domain');
        $uid        = Base::getUid();
        
        $post       = LinkModel::getDomain($domain, $uid['id']); 
        Base::PageError404($post);
        
        $result = Array();
        foreach ($post as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Base::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $result[$ind]                   = $row;
         
        }
        
        $info_domain    = LinkModel::getLinkOne($domain);
        $domains        = LinkModel::getDomainsTop($domain); 
        
        $meta_title = lang('Domain') . ': ' . $domain .' | '. Config::get(Config::PARAM_NAME);
        $meta_desc = lang('domain-desc') . ': ' . $domain .' '. Config::get(Config::PARAM_HOME_TITLE);
        
        $data = [
            'h1'            => lang('Domain') . ': ' . $domain,  
            'canonical'     => '/' . $domain,
            'sheet'         => 'domain',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc, 
        ];
        
        return view(PR_VIEW_DIR . '/link/domain', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'domains' => $domains, 'info_domain' => $info_domain]);
    }

}
