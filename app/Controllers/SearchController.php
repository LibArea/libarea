<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Lori\Base;

class SearchController extends \MainController
{
    // Форма поиска
    public function index()
    {

        if (Request::getPost())
        {    
            $query =  \Request::getPost('q');
    
            if (!empty($query)) 
            { 
                if (Base::getStrlen($query) < 3) {
                    Base::addMsg(lang('Too short'), 'error');
                    redirect('/search');
                } else if (Base::getStrlen($query) > 128) {
                    Base::addMsg(lang('Too long'), 'error');
                    redirect('/search');
                } else {
                    $qa =  SearchModel::getSearch($query);
                } 
                
                $Parsedown = new Parsedown(); 
                $Parsedown->setSafeMode(true); // безопасность
                
                $result = Array();
                foreach($qa as $ind => $row){
                    $row['post_content']  = $Parsedown->line(Base::cutWords($row['post_content'], 120, '...'));
                    $result[$ind]         = $row; 
                }     
                
            } else {
                Base::addMsg(lang('Empty request'), 'error');
                redirect('/search');
            }  
        } else {
            $query  = '';
            $result = '';
        }
        
        Base::Meta(lang('Search'), lang('search-desc'), $other = false);
        
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Search'),
            'canonical' => '/search'
        ];

        return view(PR_VIEW_DIR . '/search/index', ['data' => $data, 'uid' => $uid, 'result' => $result, 'query' => $query]);
    }
    // Поиск по домену
    public function domain() 
    {
        $domain     = \Request::get('domain');
        $account    = \Request::getSession('account');
        $user_id    = (!$account) ? 0 : $account['user_id'];
        $post       = SearchModel::getDomain($domain, $user_id); 
 
        // Покажем 404
        if(!$post) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $result = Array();
        foreach($post as $ind => $row){
            $row['post_content_preview']    = Base::cutWords($row['post_content'], 68);
            $row['post_date']               = Base::ru_date($row['post_date']);
            $row['lang_num_answers']        = Base::ru_num('answ', $row['post_answers_num']);
            $result[$ind]                   = $row;
         
        }
        
        $meta_title = lang('Domain') . ': ' . $domain;
        $meta_desc = lang('domain-desc') . ': ' . $domain;
        Base::Meta($meta_title, $meta_desc, $other = false);
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Domain') . ': ' . $domain,  
            'canonical'     => '/' . $domain,
        ];
        
        return view(PR_VIEW_DIR . '/search/domain', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
}
