<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use ImageUpload;
use Base;

class SpaceController extends \MainController
{

    // Все пространства сайта
    public function index()
    {
        $account   = \Request::getSession('account');
        $user_id = (!$account) ? 0 : $account['user_id'];

        $space = SpaceModel::getSpaceAll($user_id);

        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Все пространства',
            'title'         => 'Все пространства | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница всех пространств сайта на ' . $GLOBALS['conf']['sitename'],
        ];

        return view("space/all", ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }

    // Посты по пространству
    public function SpacePosts()
    {
        $account    = \Request::getSession('account');
        $user_id    = (!$account) ? 0 : $account['user_id'];

        // Информация по пространству и посты
        $slug  = \Request::get('slug');
        $space = SpaceModel::getSpaceInfo($slug);
        $posts = SpaceModel::getSpacePosts($space['space_id'], $user_id);
  
        if(!$space['space_img'] ) {
            $space['space_img'] = 'space_no.png';
        } 
  
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
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
 
        $slug  = \Request::get('slug');
        $space = SpaceModel::getSpaceInfo($slug);

        if(!$space['space_img'] ) {
            $space['space_img'] = 'space_no.png';
        } 
     
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
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        } 
        
        $space_slug = \Request::getPost('space_slug');
        $space_id   = \Request::getPost('space_id');
        
   
        // Узнаем преждний img
        $space = SpaceModel::getSpaceImg($space_id);
           
        $name     = $_FILES['image']['name'];
        
        if($name) {
            $size     = $_FILES['image']['size'];
            $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $width_h  = getimagesize($_FILES['image']['tmp_name']);
           
            $valid =  true;
            if (!in_array($ext, array('jpg','jpeg','png','gif'))) {
                $valid = false;
                Base::addMsg('Тип файла не разрешен', 'error');
                redirect('/space/'.$space_slug.'/edit');
            }

            // Проверка ширины, высоты и размера
            if ($width_h['0'] > 150) {
                $valid = false;
                Base::addMsg('Ширина больше 150 пикселей', 'error');
                redirect('/space/'.$space_slug.'/edit');
            }
            if ($width_h['1'] > 150) {
                $valid = false;
                Base::addMsg('Высота больше 150 пикселей', 'error');
                redirect('/space/'.$space_slug.'/edit');
            }
            if ($size > 50000) {
                $valid = false;
                Base::addMsg('Размер файла превышает допустимый', 'error');
                redirect('/space/'.$space_slug.'/edit');
            }

            if ($valid) {
                // 110px и 18px
                $path_img       = HLEB_PUBLIC_DIR. '/uploads/space/';
                $path_img_small = HLEB_PUBLIC_DIR. '/uploads/space/small/';
                
                $image = new ImageUpload('image'); 
                
                $image->resize(110, 110, 'crop');            
                $img = $image->saveTo($path_img, $space_id . '_space');
                
                $image->resize(18, 18);            
                $image->saveTo($path_img_small, $space_id. '_space');
                
                if($space['space_img']){
                    chmod($path_img . $space['space_img'], 0777);
                    chmod($path_img_small . $space['space_img'], 0777);
                    unlink($path_img . $space['space_img']);
                    unlink($path_img_small . $space['space_img']);
                }  
                $space_img = $img;
            } else {
                $space_img = (empty($space['space_img'])) ? '' : $space['space_img'];
            }
            
        } else {
            $space_img = (empty($space['space_img'])) ? '' : $space['space_img'];
        }
        
        $space_color = \Request::getPost('space_color');
        $space_color = (empty($space_color)) ? '' : $space_color;
        
        // Пока перечислим все поля
        // Если пользователи смогут создавать пространства с TL2
        // То данный метод следует изменить
        $data = [
            'space_id'          => $space_id,
            'space_slug'        => $space_slug,
            'space_name'        => \Request::getPost('space_name'),
            'space_description' => \Request::getPost('space_description'),
            'space_color'       => $space_color,
            'space_text'        => $_POST['space_text'], // Не фильтруем!
            'space_img'         => $space_img,
        ]; 
        
        SpaceModel::setSpaceEdit($data);
        
        Base::addMsg('Изменение сохранено', 'error');
        redirect('/s/' . $data['space_slug']);
    }
    
    // Отписка от пространств
    public function hide()
    {
        $space_id = \Request::getPostInt('space_id'); 
        $account  = \Request::getSession('account');

        SpaceModel::SpaceHide($space_id, $account['user_id']);
        
        return true;
    }

}
