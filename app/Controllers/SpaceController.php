<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use Base;

class SpaceController extends \MainController
{

    // Все пространства сайта
    public function index()
    {

        $data = [
            'space'  => SpaceModel::getSpaceHome(),
            'title' => 'Пространства сайта',
            'msg'   => Base::getMsg(),
        ];

        return view("space/all", ['data' => $data]);
    }

    // Посты по пространству
    public function SpacePosts()
    {
 
        $space = Request::get('space');
 
        $posts = SpaceModel::getSpacePosts($space);
 
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
            $space_hide = SpaceModel::getMySpaceHide($result[0]['space_id'], $user['user_id']);
            
        } else {
             $space_hide = NULL;
             
        }
        
        $data = [
            'posts'      => $result,
            'space'      => $space,
            'space_hide' => $space_hide,
            'title'      => 'Пространство',
            'msg'        => Base::getMsg(),
        ];

        return view("space/spaceposts", ['data' => $data]);
        
    }

    // Изменение тега
    public function spaceForma()
    {
        
        $data = [
            'space' => SpaceModel::getSpaceHome(),
            'msg'   => Base::getMsg(),
        ]; 
 
        return view("space/formaspace", ['data' => $data]);
        
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
        
        $space_id = Request::get('id'); 
        
        SpaceModel::SpaceHide($space_id, $account['user_id']);
        
        return true;
        
    }

}
