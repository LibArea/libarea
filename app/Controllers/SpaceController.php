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

       $space = SpaceModel::getSpaceHome();

        $uid  = Base::getUid();
        $data = [
            'h1'       => 'Все пространства',
            'title'       => 'Все пространства | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Страница всех пространств сайта на ' . $GLOBALS['conf']['sitename'],
        ];

        return view("space/all", ['data' => $data, 'uid' => $uid, 'space' => $space]);
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
            $row['num_comments']  = Base::ru_num('comm', $row['post_comments']);
            $result[$ind]         = $row;
         
        }  

        // Отписан участник от тега или нет
        if(Request::getSession('account')) {
            
            $user = Request::getSession('account');
            $space_hide = SpaceModel::getMySpaceHide($result[0]['space_id'], $user['user_id']);
            
        } else {
             $space_hide = NULL;
             
        }
 
        $uid  = Base::getUid();
        $data = [
            'h1'         => 'Посты по пространству ' . $result[0]['space_name'],
            'title'      => $space . ' - посты по пространству | ' . $GLOBALS['conf']['sitename'],
            'description'=> 'Страница постов по пространству ' . $result[0]['space_name'] . ' на сайте ' . $GLOBALS['conf']['sitename'],
            'space_hide' => $space_hide,
        ];

        return view("space/spaceposts", ['data' => $data, 'uid' => $uid, 'posts' => $result]);
        
    }

    // Изменение пространства (в стадии разработки)
    public function spaceForma()
    {
        
        // Доступ только персоналу
        $account = Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $space = Request::get('space');
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Изменение пространства',
            'title'         => 'Изменение пространства | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница изменения пространства на' . $GLOBALS['conf']['sitename'],
        ]; 

        return view("space/formaspace", ['data' => $data, 'uid' => $uid, 'space' => $space]);
        
    }
    
    // Отписка тегов
    public function hide()
    {

        $space_id = \Request::getPostInt('space_id'); 

        $account = Request::getSession('account');
        SpaceModel::SpaceHide($space_id, $account['user_id']);
        
        return true;
        
    }

}
