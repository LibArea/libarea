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

        $space = SpaceModel::getSpaceAll();

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
 
        if($account = Request::getSession('account')){
            $user_id = $account['user_id'];
        } else {
            $user_id = 0;
        }
 
        // Информация по пространству и посты
        $slug  = Request::get('slug');
        $space = SpaceModel::getSpaceInfo($slug);
        $posts = SpaceModel::getSpacePosts($space['space_id'], $user_id);
  
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

        // Отписан участник от пространства или нет
        $space_hide = SpaceModel::getMySpaceHide($space['space_id'], $user_id);
 
        $uid  = Base::getUid();
        $data = [
            'h1'         => $space['space_name'],
            'title'      => $space['space_name'] . ' - посты по пространству | ' . $GLOBALS['conf']['sitename'],
            'description'=> 'Страница постов по пространству ' . $space['space_name'] . ' на сайте ' . $GLOBALS['conf']['sitename'],
            'space_hide' => $space_hide,
        ];

        return view("space/spaceposts", ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space' => $space]);
        
    }

    // Форма изменения пространства
    public function spaceForma()
    {
        
        // Доступ только персоналу
        $account = Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $slug  = Request::get('slug');
        $space = SpaceModel::getSpaceInfo($slug);
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Изменение - ' . $slug,
            'title'         => 'Изменение пространства | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница изменения пространства на' . $GLOBALS['conf']['sitename'],
        ]; 

        return view("space/formaspace", ['data' => $data, 'uid' => $uid, 'space' => $space]);
        
    }
    
    // Изменение пространства
    public function spaceEdit() 
    {
        // Доступ только персоналу
        $account = Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        } 
        
        // Пока перечислим все поля
        // Если пользователи смогут создавать пространства с TL2
        // То данный метод следует изменить
        $data = [
            'space_id'          => \Request::getPost('space_id'),
            'space_slug'        => \Request::getPost('space_slug'),
            'space_name'        => \Request::getPost('space_name'),
            'space_description' => \Request::getPost('space_description'),
            'space_color'       => \Request::getPost('space_color'),
            'space_text'        => $_POST['space_text'], // Не фильтруем!
        ]; 
        
        SpaceModel::setSpaceEdit($data);
        
        Base::addMsg('Изменение сохранено', 'error');
        redirect('/s/' . $data['space_slug']);
        
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
