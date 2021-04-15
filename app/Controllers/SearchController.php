<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
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
                    $row['post_title']    = $row['post_title'];
                    $row['post_content']  = $Parsedown->line(mb_substr($row['post_content'],0,120, 'utf-8').'...'); 
                    $row['post_slug']     = $row['post_slug'];             
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
    
}
