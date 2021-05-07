<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use ImageUpload;
use Lori\Config;
use Lori\Base;

class SpaceController extends \MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $space  = SpaceModel::getSpaceAll($uid['id']);

        // Введем ограничение на количество создаваемых пространств
        $sp             = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($sp);

        $data = [
            'h1'            => 'Все пространства',
            'canonical'     => '/space', 
        ];

        $meta_title = 'Все пространства';
        $meta_desc  = 'Все пространства сообщества';
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);
        
        return view(PR_VIEW_DIR . '/space/all', ['data' => $data, 'uid' => $uid, 'space' => $space, 'count_space' => $count_space]);
    }

    // Посты по пространству
    public function SpacePosts($type)
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $space = SpaceModel::getSpaceInfo($space_slug);
    
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
  
        $posts = SpaceModel::getSpacePosts($space['space_id'], $uid['id'], $space_tags_id, $type);

        $space['space_date']        = Base::ru_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        
        $result = Array();
        foreach($posts as $ind => $row){
            $row['post_content_preview']    = Base::cutWords($row['post_content'], 68);
            $row['lang_num_answers']        = Base::ru_num('answ', $row['post_answers_num']);
            $result[$ind]                   = $row;
        }  

        $tags = SpaceModel::getSpaceTags($space['space_id']);

        // Отписан участник от пространства или нет
        $space_signed = SpaceModel::getMySpaceHide($space['space_id'], $uid['id']);
        
        if($type == 'feed') {
            $s_title = lang('space-feed-title');
        } else {
            $s_title = lang('space-top-title');
        }

        $data = [
            'h1'            => $space['space_name'],
            'canonical'     => '/s/' . $space['space_slug'], 
        ];

        $meta_title = $space['space_name'] . ' — ' . $s_title;
        $meta_desc  = $space['space_name'] . ' — ' . $s_title . '. ';
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);

        return view(PR_VIEW_DIR . '/space/space-posts', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_info' => $space, 'tags' => $tags, 'space_signed' => $space_signed, 'type' => $type]);
    }

    // Форма изменения пространства
    public function spaceForma()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);

        // Или персонал или автор
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }

        $data = [
            'h1'            => 'Изменение - ' . $slug,
            'canonical'     => '/***', 
        ];

        $meta_title = 'Изменение - ' . $slug;
        $meta_desc  = 'Изменение - ' . $slug;
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);

        return view(PR_VIEW_DIR . '/space/edit-space', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница с информацией по меткам
    public function spaceTagsInfo() 
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);

        // Или персонал или автор
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }
        
        $tags = SpaceModel::getSpaceTags($space['space_id']);
        
        $data = [
            'h1'            => 'Метки',
            'canonical'     => '/***', 
        ];

        // title, description
        Base::Meta('Метки', 'Метки', $other = false);
 
        return view(PR_VIEW_DIR . '/space/info-space', ['data' => $data, 'uid' => $uid, 'space' => $space, 'tags' => $tags]);
    }
    
    // Форма добавления пространства
    public function addSpacePage() 
    {
        $uid  = Base::getUid();
  
        // Для пользователя с TL < 2 редирект    
        if ($uid['trust_level'] < 2) {
            redirect('/');
        }  
  
        // Введем ограничение на количество создаваемых пространств
        $space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($space);
        
        // Пока 3, далее привязать к TL
        if ($count_space >= 3) {
            redirect('/');
        }  
 
        $num_add_space = 3 - $count_space;
 
        // Если пользователь уже создал пространство
        // Ограничить по TL количество + не показывать кнопку добавления
     
        $data = [
            'h1'          => 'Добавить пространство',
            'canonical'     => '/***', 
        ];

        $meta_title = 'Добавить пространство';
        $meta_desc  = 'Добавить пространство';
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);
        
        return view(PR_VIEW_DIR . '/space/add-space', ['data' => $data, 'uid' => $uid, 'num_add_space' => $num_add_space]);
    }
    
    // Добавления пространства
    public function spaceAdd() 
    {
        $uid  = Base::getUid();
        
        // Для пользователя с TL < N       
        if ($uid['trust_level'] < Config::get(Config::PARAM_SPACE)) {
            redirect('/');
        }  
        
        $space_slug     = \Request::getPost('space_slug');
        $space_name     = \Request::getPost('space_name');  
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
     
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $space_slug))
        {
            Base::addMsg('В URL можно использовать только латиницу, цифры', 'error');
            redirect('/space/add');
        }
        
        
        // Проверяем длину
        $redirect   = '/space/add';
        $txt        = 'Длина названия должна быть от 6 до 20 знаков';
        Base::Limits($space_name, '6', '20', $txt, $redirect);
  
        $txt        = 'URL должно быть от 3 до ~ 15 символов';
        Base::Limits($space_slug, '4', '15', $txt, $redirect);
        
        if (preg_match('/\s/', $space_slug) || strpos($space_slug,' '))
        {
            Base::addMsg('В URL не допускаются пробелы', 'error');
            redirect('/space/add');
        }
        if (SpaceModel::getSpaceInfo($space_slug)) {
            Base::addMsg('Такой URL пространства уже есть', 'error');
            redirect('/space/add');
        }
        
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;

        $data = [
            'space_name'            => $space_name,
            'space_slug'            => $space_slug,
            'space_description'     => '',
            'space_color'           => 0,
            'space_img'             => 'space_no.png',
            'space_text'            => '',
            'space_date'            => date("Y-m-d H:i:s"),
            'space_category_id'     => 1,
            'space_user_id'         => $uid['id'],
            'space_type'            => 0, 
            'space_permit_users'    => $space_permit,  
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
            'space_is_delete'       => 0,
        ];
 
        // Добавляем пространство
        SpaceModel::AddSpace($data);

        Base::addMsg('Пространство успешно добавлено', 'success');
        redirect('/space'); 
    }
    
    // Изменение пространства
    public function spaceEdit() 
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::getPost('space_slug');
        $space_id       = \Request::getPost('space_id');
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
        
        $space = SpaceModel::getSpaceId($space_id);

        // Или персонал или владелец
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }

        $space_name         = \Request::getPost('space_name');
        $space_description  = \Request::getPost('space_description');
        $space_text         = \Request::getPost('space_text');

        // Проверяем длину
        $redirect   = '/space/' . $space['space_slug'] . '/edit';
        $txt        = 'Длина названия должна быть от 4 до 20 знаков';
        Base::Limits($space_name, '4', '20', $txt, $redirect);
  
        $txt        = 'Длина meta- описания должна быть от 60 до 190 знаков';
        Base::Limits($space_description, '60', '190', $txt, $redirect);

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
                
                // Удалим, кроме дефолтной
                if($space['space_img'] != 'space_no.png'){
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
        $space_color = (empty($space_color)) ? 0 : $space_color;
        
       
        $slug = SpaceModel::getSpaceInfo($space_slug);

        if($slug['space_slug'] != $space['space_slug']) {
            if($slug) {
                Base::addMsg('Такой URL пространства уже есть', 'error');
                redirect('/s/'.$space['space_slug']);
            }
        }
  
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;
        
        $data = [
            'space_id'              => $space_id,
            'space_slug'            => $space_slug,
            'space_name'            => $space_name,
            'space_description'     => $space_description,
            'space_color'           => $space_color,
            'space_text'            => $space_text,
            'space_img'             => $space_img,
            'space_permit_users'    => $space_permit,
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
        ]; 
        
        SpaceModel::setSpaceEdit($data);
        
        Base::addMsg('Изменение сохранено', 'success');
        redirect('/s/' . $data['space_slug']);
    }
    
    // Страница добавления метки (тега) пространства
    public function spaceTagsAddPage()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);
        
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        // Добавлять может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
      
        $data = [
            'h1'        => 'Добавить метку',
            'canonical' => '/***', 
        ];

        // title, description
        Base::Meta('Добавить метку', 'Добавить метку', $other = false);
        
        return view(PR_VIEW_DIR . '/space/add-tag', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница изменение тега пространства
    public function editTagSpacePage()
    {
        $uid            = Base::getUid();
        $slug           = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $space = SpaceModel::getSpaceInfo($slug);
    
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $tag = SpaceModel::getTagInfo($space_tags_id);
        
        // Покажем 404
        if(!$tag) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $data = [
            'h1'         => 'Изменить тэг',
            'canonical'     => '/***', 
        ];

        // title, description
        Base::Meta('Изменить тэг', 'Изменить тэг', $other = false);

        return view(PR_VIEW_DIR . '/space/edit-tag', ['data' => $data, 'uid' => $uid, 'tag' => $tag]);
    }
    
    // Изменяем тег пространства
    public function editTagSpace()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $tag_id     = \Request::getPostInt('tag_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        // Проверяем длину title
        if (Base::getStrlen($st_title) < 4 || Base::getStrlen($st_title) > 20)
        {
            Base::addMsg('Длина метки должна быть от 4 до 20 знаков', 'error');
            redirect('/s/' . $space['space_slug'] . '/' . $tag_id . '/edit');
            return true;
        }
        
        // Проверяем длину описания
        if (Base::getStrlen($st_desc) < 30 || Base::getStrlen($st_desc) > 180)
        {
            Base::addMsg('Длина поста должна быть от 30 до 180 знаков', 'error');
            redirect('/s/' . $space['space_slug'] . '/' . $tag_id . '/edit');
            return true;
        }

        SpaceModel::tagEdit($tag_id, $st_title, $st_desc);

        Base::addMsg('Тэг успешно изменен', 'success');
        redirect('/s/' .$space['space_slug']);
    }
    
    // Подписка / отписка от пространств
    public function hide()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id'); 
        $account    = \Request::getSession('account');

        // Запретим действия если участник создал пространство
        $sp_info    = SpaceModel::getSpaceId($space_id);
        if($sp_info['space_user_id'] == $uid['id']) {
            return false;
        }

        SpaceModel::SpaceHide($space_id, $account['user_id']);
        
        return true;
    }

    // Добавления тега
    public function addTagSpace() 
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        // Проверяем длину title
        if (Base::getStrlen($st_title) < 4 || Base::getStrlen($st_title) > 20)
        {
            Base::addMsg('Длина метки должна быть от 4 до 20 знаков', 'error');
            redirect('/space/' . $space['space_slug'] . '/tags/add');
            return true;
        }
  
        // Проверяем длину описания
        if (Base::getStrlen($st_desc) < 20 || Base::getStrlen($st_desc) > 180)
        {
            Base::addMsg('Длина поста должна быть от 20 до 180 знаков', 'error');
            redirect('/space/' . $space['space_slug'] . '/tags/add');
            return true;
        }
        
        // Добавим
        SpaceModel::tagAdd($space['space_id'], $st_title, $st_desc);
        
        Base::addMsg('Метка успешно добавлена', 'success');
        redirect('/s/' . $space['space_slug']);
    }

}
