<?php

namespace App\Controllers;
use App\Models\TagModel;
use Hleb\Constructor\Handlers\Request;
use Base;

class TagController extends \MainController
{

    // Все теги сайта
    public function index()
    {

        $data = [
            'tags'  => TagModel::getTagHome(),
            'title' => 'Теги сайта',
            'msg'   => Base::getMsg(),
        ];

        return view("tag/all", ['data' => $data]);
    }

    // Посты по тегу
    public function tagPosts()
    {
 
        $tag = Request::get('tag');
 
        $posts = TagModel::getTagPosts($tag);
 
        // Покажем 404
        if(!$posts) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar']  = 'noavatar.png';
            }  
            $row['avatar']        = $row['avatar'];
            $row['post_votes']    = $row['post_votes'];
            $row['date']          = $row['post_date'];
            $row['num_comments']  = $row['post_comments']; 
            $row['post_comments'] = Base::ru_num('comm', $row['post_comments']);
            $result[$ind]         = $row;
         
        }  

        // Отписан участник от тега или нет
        if(Request::getSession('account')) {
            
            $user = Request::getSession('account');
            $tag_hide = TagModel::getMyTagHide($result[0]['tags_id'], $user['user_id']);
            
        } else {
             $tag_hide = NULL;
             
        }
        
        $data = [
            'posts'    => $result,
            'tag'      => $tag,
            'tag_hide' => $tag_hide,
            'title'    => 'Посты по тегу',
            'msg'      => Base::getMsg(),
        ];

 
        return view("tag/tagposts", ['data' => $data]);
        
    }

    // Изменение тега
    public function tagForma()
    {
        
        $data = [
            'tags' => TagModel::getTagsHome(),
            'msg'   => Base::getMsg(),
        ]; 
 
        return view("tag/formatag", ['data' => $data]);
        
    }
    
    // Отписка тегов
    public function hide()
    {

        if (!Request::getSession('account'))
        {
            return false;
        } else {
            $account = Request::getSession('account');
        } 
        
        $tag_id = Request::get('id'); 
        
        TagModel::TagHide($tag_id, $account['user_id']);
        
        return true;
        
    }

}
