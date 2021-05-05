<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Parsedown;
use Base;

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
                    Base::addMsg('Слишком короткий поисковый запрос', 'error');
                    redirect('/search');
                } else if (Base::getStrlen($query) > 128) {
                    Base::addMsg('Слишком длинный поисковый запрос', 'error');
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
                Base::addMsg('Задан пустой поисковый запрос.', 'error');
                redirect('/search');
            }  
        } else {
            $query  = '';
            $result = '';
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'         => lang('Search') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница поиска по сайту ' . $GLOBALS['conf']['sitename'],
            'h1'            => lang('Search'),
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
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $result = Array();
        foreach($post as $ind => $row){
            $row['post_content_preview']    = $Parsedown->line(Base::cutWords($row['post_content'], 68));
            $row['post_date']               = Base::ru_date($row['post_date']);
            $row['lang_num_answers']        = Base::ru_num('answ', $row['post_answers_num']);
            $result[$ind]                   = $row;
         
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Domain') . ': ' . $domain,  
            'title'         => lang('Domain') . ': ' . $domain . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('domain-desc') . ' - ' . $domain . ' на сайте '. $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/search/domain', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
}
