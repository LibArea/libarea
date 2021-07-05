<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use Lori\UploadImage;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class SpaceController extends \MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $space  = SpaceModel::getSpaces($uid['id']); 

        // Введем ограничение на количество создаваемых пространств
        $sp             = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($sp);
        $add_space_button = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);

        $result = Array();
        foreach ($space as $ind => $row) {
            $row['users']   = SpaceModel::numSpaceSubscribers($row['space_id']);
            $result[$ind]   = $row;
        }  
        
        Request::getHead()->addStyles('/assets/css/space.css');
        
        $data = [
            'h1'            => lang('All space'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/space',
            'sheet'         => 'spaces', 
            'meta_title'    => lang('All space') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('all-space-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/space/spaces', ['data' => $data, 'uid' => $uid, 'space' => $result, 'add_space_button' => $add_space_button]);
    }

    // Пространства участника
    public function spaseUser()
    {
        $uid            = Base::getUid();
        $space          = SpaceModel::getSpaceUserSigned($uid['id']);

        // Введем ограничение на количество создаваемых пространств
        $all_space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space        = count($all_space);
        $add_space_button   = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);

        $result = Array();
        foreach ($space as $ind => $row) {
            $row['users']   = SpaceModel::numSpaceSubscribers($row['space_id']);
            $result[$ind]   = $row;
        }  
        
        Request::getHead()->addStyles('/assets/css/space.css');

        $data = [
            'h1'            => lang('I read space'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/space', 
            'sheet'         => 'my-space', 
            'meta_title'    => lang('I read space') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('I read space') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/space/all-space', ['data' => $data, 'uid' => $uid, 'space' => $result, 'add_space_button' => $add_space_button]);
    }

    // Посты по пространству
    public function posts($sheet)
    {
        $uid            = Base::getUid();
        $slug           = \Request::get('slug');
        
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        
        // Покажем 404
        $space = SpaceModel::getSpaceSlug($slug);
        Base::PageError404($space);
  
        $pagesCount = SpaceModel::getCount($space['space_id'], $uid['id'], $uid['trust_level'], $sheet, $page); 
        $posts      = SpaceModel::getPosts($space['space_id'], $uid['id'], $uid['trust_level'], $sheet, $page);

        $space['space_date']        = lang_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        $space['space_text']        = Content::text($space['space_text'], 'text');
        
        $result = Array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }  

        $space['users'] = SpaceModel::numSpaceSubscribers($space['space_id']);

        // Отписан участник от пространства или нет
        $space_signed = SpaceModel::getMyFocus($space['space_id'], $uid['id']);
        
        if ($sheet == 'feed') {
            $s_title = lang('space-feed-title');
        } else {
            $s_title = lang('space-top-title');
        }

        Request::getHead()->addStyles('/assets/css/space.css');


        if ($page > 1) { 
            $num = ' | ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }

        $data = [
            'h1'            => $space['space_name'],
            'canonical'     => Config::get(Config::PARAM_URL) .'/s/'. $space['space_slug'],
            'img'           => Config::get(Config::PARAM_URL) .'/uploads/spaces/logos/'. $space['space_img'],
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'sheet'         => $sheet,
            'meta_title'    => $space['space_name'] .' — '. $s_title .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => $space['space_description'] .' '. $s_title .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/space/space-posts', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_info' => $space, 'space_signed' => $space_signed]);
    }

    // Форма изменения пространства
    public function editForm()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');

        $space = SpaceModel::getSpaceSlug($slug);
        
        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        } 

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        
        $data = [
            'h1'            => lang('Change') . ' - ' . $slug,
            'sheet'         => 'edit-space', 
            'meta_title'    => lang('Edit') . ' / ' . $slug,
        ];

        return view(PR_VIEW_DIR . '/space/edit-space', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Форма изменения логотипа и обложки
    public function logoForm()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');

        $space = SpaceModel::getSpaceSlug($slug);

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        } 

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        
        $data = [
            'h1'            => lang('Logo') . ' - ' . $slug,
            'sheet'         => 'edit-logo', 
            'meta_title'    => lang('Edit') . ' / ' . lang('Logo'),
        ];

        return view(PR_VIEW_DIR . '/space/edit-space-logo', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Форма добавления пространства
    public function addForm() 
    {
        $uid  = Base::getUid();
  
        // Если пользователь уже создал пространство, то ограничим их количество
        $space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($space);
        
        // Для пользователя с TL < N   
        $valid = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);
        if ($valid === false) {
            redirect('/');
        }
 
        $num_add_space = 3 - $count_space;
 
        $data = [
            'h1'            => lang('Add Space'),
            'sheet'         => 'add-space', 
            'meta_title'    => lang('Add Space'),
        ];

        return view(PR_VIEW_DIR . '/space/add-space', ['data' => $data, 'uid' => $uid, 'num_add_space' => $num_add_space]);
    }
    
    // Добавления пространства
    public function add() 
    {
        $uid  = Base::getUid();
        
        // Проверка на случай хакинга формы
        $space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($space);
        
        $valid = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);
        if ($valid === false) {
            redirect('/');
        }
        
        $space_slug     = \Request::getPost('space_slug');
        $space_name     = \Request::getPost('space_name');  
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
     
        $redirect   = '/space/add';
        if (!preg_match('/^[a-zA-Z0-9_]+$/u', $space_slug)) {
            Base::addMsg(lang('url-latin'), 'error');
            redirect($redirect);
        }
        
        Base::Limits($space_name, lang('titles'), '4', '20', $redirect);
        Base::Limits($space_slug, 'slug (URL)', '3', '10', $redirect);
        
        if (preg_match('/\s/', $space_slug) || strpos($space_slug,' ')) {
            Base::addMsg(lang('url-gaps'), 'error');
            redirect($redirect);
        }
        if (SpaceModel::getSpaceSlug($space_slug)) {
            Base::addMsg(lang('url-already-exists'), 'error');
            redirect($redirect);
        }
        
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;

        $data = [
            'space_name'            => $space_name,
            'space_slug'            => $space_slug,
            'space_description'     => '',
            'space_color'           => '#fa6807',
            'space_img'             => 'space_no.png',
            'space_text'            => '',
            'space_short_text'      => '',
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

        Base::addMsg(lang('space-add-success'), 'success');
        redirect('/space'); 
    }
    
    // Изменение пространства
    public function edit() 
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::getPost('space_slug');
        $space_id       = \Request::getPostInt('space_id');
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        $space_name         = \Request::getPost('space_name');
        $space_description  = \Request::getPost('space_description');
        $space_text         = \Request::getPost('space_text');
        $space_short_text   = \Request::getPost('space_short_text');

        $redirect   = '/space/' . $space['space_slug'] . '/edit';
        if (!preg_match('/^[a-zA-Z0-9_]+$/u', $space_slug)) {
            Base::addMsg(lang('url-latin'), 'error');
            redirect($redirect);
        }

        Base::Limits($space_name, lang('titles'), '4', '20', $redirect);
        Base::Limits($space_description, 'Meta-', '60', '190', $redirect);
        Base::Limits($space_slug, 'SLUG', '3', '10', $redirect);
        Base::Limits($space_short_text, 'TEXT', '20', '250', $redirect);

        $space_color = \Request::getPost('color');
        $space_color = empty($space_color) ? $space['space_color'] : $space_color;
        
        $slug = SpaceModel::getSpaceSlug($space_slug);

        if ($slug['space_slug'] != $space['space_slug']) {
            if ($slug) {
                Base::addMsg(lang('url-already-exists'), 'error');
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
            'space_short_text'      => $space_short_text,
            'space_permit_users'    => $space_permit,
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
        ]; 
        
        SpaceModel::edit($data);
        
        Base::addMsg(lang('Change saved'), 'success');
        redirect('/s/' . $space_slug);
    }
    
    
    // Изменение логотипа и обложки
    public function logoEdit() 
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::getPost('space_slug');
        $space_id       = \Request::getPost('space_id');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        } 

        $redirect   = '/space/' . $space['space_slug'] . '/edit/logo';

        // Запишем img
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if($check_img) {
            UploadImage::img($img, $space['space_id'], 'space');
        }   

        // Запишем баннер
        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'][0];
        if($check_cover) {
            UploadImage::cover($cover, $space['space_id'], 'space');
        } 
 
        Base::addMsg(lang('Change saved'), 'success');
        redirect($redirect);
    }
    
    // Удаляем обложку
    public function coverRemove()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        

        $space = SpaceModel::getSpaceSlug($slug);
        
        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        } 
      
        $redirect   = '/space/' . $space['space_slug'] . '/edit/logo'; 
        
        // 1920px и 300px
        $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/';
        $path_cover_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/small/';

        // Удалим, кроме дефолтной
        if ($space['space_cover_art'] != 'space_cover_no.jpeg') {
            unlink($path_cover_img . $space['space_cover_art']);
            unlink($path_cover_img_small . $space['space_cover_art']);
        }  
        
        SpaceModel::CoverRemove($space['space_id']);
        
        Base::addMsg(lang('Cover removed'), 'success');
        redirect($redirect);
    }
    
    // Подписка / отписка от пространств
    public function focus()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id'); 

        // Запретим действия если участник создал пространство
        $sp_info    = SpaceModel::getSpaceId($space_id);
        if ($sp_info['space_user_id'] == $uid['id']) {
            return false;
        }

        SpaceModel::focus($space_id, $uid['id']);
        
        return true;
    }
}
