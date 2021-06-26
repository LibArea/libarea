<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use SimpleImage;
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
        
        return view(PR_VIEW_DIR . '/space/all-space', ['data' => $data, 'uid' => $uid, 'space' => $result, 'count_space' => $count_space]);
    }

    // Пространства участника
    public function spaseUser()
    {
        $uid            = Base::getUid();
        $space          = SpaceModel::getSpaceUserSigned($uid['id']);
        $count_space    = count($space);

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
        
        return view(PR_VIEW_DIR . '/space/all-space', ['data' => $data, 'uid' => $uid, 'space' => $result, 'count_space' => $count_space]);
    }

    // Посты по пространству
    public function posts($type)
    {
        $uid            = Base::getUid();
        $slug           = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        
        // Покажем 404
        $space = SpaceModel::getSpaceInfo($slug);
        Base::PageError404($space);
  
        $pagesCount = SpaceModel::getCount($space['space_id'], $uid['id'], $uid['trust_level'], $space_tags_id, $type, $page); 
        $posts      = SpaceModel::getPosts($space['space_id'], $uid['id'], $uid['trust_level'], $space_tags_id, $type, $page);

        $space['space_date']        = lang_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        $space['space_text']        = Content::text($space['space_text'], 'text');
        
        $result = Array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $result[$ind]                   = $row;
        }  

        $tags           = SpaceModel::getSpaceTags($space['space_id']);
        $space['users'] = SpaceModel::numSpaceSubscribers($space['space_id']);

        // Отписан участник от пространства или нет
        $space_signed = SpaceModel::getMySpaceHide($space['space_id'], $uid['id']);
        
        if($type == 'feed') {
            $s_title = lang('space-feed-title');
        } else {
            $s_title = lang('space-top-title');
        }

        Request::getHead()->addStyles('/assets/css/space.css');


        if($page > 1) { 
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
            'sheet'         => 'post-space',
            'meta_title'    => $space['space_name'] .' — '. $s_title .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => $space['space_description'] .' '. $s_title .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/space/space-posts', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_info' => $space, 'tags' => $tags, 'space_signed' => $space_signed, 'type' => $type]);
    }

    // Форма изменения пространства
    public function editForm()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');

        $space = SpaceModel::getSpaceInfo($slug);
        
        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 

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

        $space = SpaceModel::getSpaceInfo($slug);

        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        
        $data = [
            'h1'            => lang('Logo') . ' - ' . $slug,
            'sheet'         => 'edit-logo', 
            'meta_title'    => lang('Edit') . ' / ' . lang('Logo'),
        ];

        return view(PR_VIEW_DIR . '/space/edit-space-logo', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница с информацией по меткам
    public function tagsInfo() 
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');

        $space = SpaceModel::getSpaceInfo($slug);

        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 
        
        $tags = SpaceModel::getSpaceTags($space['space_id']);
        
        $data = [
            'h1'            => lang('Tags'),
            'sheet'         => 'edit-tags', 
            'meta_title'    => lang('Edit') . ' / ' . lang('Tags'),
        ];
 
        return view(PR_VIEW_DIR . '/space/info-space', ['data' => $data, 'uid' => $uid, 'space' => $space, 'tags' => $tags]);
    }
    
    // Форма добавления пространства
    public function addForm() 
    {
        $uid  = Base::getUid();
  
        // Для пользователя с TL < N   
        Base::validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), '/');
  
        // Если пользователь уже создал пространство, то ограничим их количество
        $space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($space);
        
        if ($count_space >= 3) {
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
        
        // Для пользователя с TL < N       
        Base::validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), '/');
        
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
        if (SpaceModel::getSpaceInfo($space_slug)) {
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
        $space_id       = \Request::getPost('space_id');
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 

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
        
        $slug = SpaceModel::getSpaceInfo($space_slug);

        if($slug['space_slug'] != $space['space_slug']) {
            if($slug) {
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
        
        SpaceModel::setSpaceEdit($data);
        
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
        Base::accessСheck($space, 'space', $uid); 

        $redirect   = '/space/' . $space['space_slug'] . '/edit/logo';

        $name = $_FILES['images']['name'][0];
        if($name) {
            // 110px и 24px
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/small/';
            $file           = $_FILES['images']['tmp_name'][0];
            $filename       =  's-' . $space['space_id'] . '-' . time();

            $image = new  SimpleImage();

            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(110, 110)
                ->toFile($path_img . $filename .'.jpeg', 'image/jpeg')
                ->resize(24, 24)
                ->toFile($path_img_small . $filename .'.jpeg', 'image/jpeg');
            
            // Удалим, кроме дефолтной
            if($space['space_img'] != 'space_no.png'){
                chmod($path_img . $space['space_img'], 0777);
                chmod($path_img_small . $space['space_img'], 0777);
                unlink($path_img . $space['space_img']);
                unlink($path_img_small . $space['space_img']);
            }  
            
            $space_img    = $filename . '.jpeg';

        } else {
            $space_img = empty($space['space_img']) ? 'space_no.png' : $space['space_img'];
        }
        
        $cover = $_FILES['cover']['name'][0];
        if($cover) {
            // 1920px и 350px
            $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/';
            $path_cover_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/small/';
            $file_cover           = $_FILES['cover']['tmp_name'][0];
            $filename_cover       =  's-' . $space['space_id'] . '-' . time();

            $image = new  SimpleImage();

            $image
                ->fromFile($file_cover)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(1920, 350)
                ->toFile($path_cover_img . $filename_cover .'.webp', 'image/webp')
                ->resize(180, 70)
                ->toFile($path_cover_img_small . $filename_cover .'.webp', 'image/webp');
                
                $cover_art = $filename_cover . '.webp';
            
            // Удалим, кроме дефолтной
            if($space['space_cover_art'] != 'space_cover_no.jpeg' && $space['space_cover_art'] != $cover_art) {
                chmod($path_cover_img . $space['space_cover_art'], 0777);
                chmod($path_cover_img_small . $space['space_cover_art'], 0777);
                unlink($path_cover_img . $space['space_cover_art']);
                unlink($path_cover_img_small . $space['space_cover_art']);
            }  
            
            $space_cover_art = $filename_cover . '.webp';

        } else {
            $space_cover_art = empty($space['space_img']) ? 'space_cover_no.jpeg' : $space['space_cover_art'];
        }
        
        $data = [
            'space_id'              => $space_id,
            'space_img'             => $space_img,
            'space_cover_art'       => $space_cover_art,
        ]; 
        
        SpaceModel::setSpaceEditLogo($data);
        
        Base::addMsg(lang('Change saved'), 'success');
        redirect($redirect);
    }
    
    // Удаляем обложку
    public function coverRemove()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        

        $space = SpaceModel::getSpaceInfo($slug);
        
        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 
      
        $redirect   = '/space/' . $space['space_slug'] . '/edit/logo'; 
        
        // 1920px и 300px
        $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/';
        $path_cover_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/small/';

        // Удалим, кроме дефолтной
        if($space['space_cover_art'] != 'space_cover_no.jpeg') {
            unlink($path_cover_img . $space['space_cover_art']);
            unlink($path_cover_img_small . $space['space_cover_art']);
        }  
        
        SpaceModel::CoverRemove($space['space_id']);
        
        Base::addMsg(lang('Cover removed'), 'success');
        redirect($redirect);
    }
    
    // Страница добавления метки (тега) пространства
    public function tagsAddForm()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        
        $space = SpaceModel::getSpaceInfo($slug);

        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 
      
        $data = [
            'h1'            => lang('Add tag'),
            'sheet'         => 'add-tag', 
            'meta_title'    => lang('Add tag'),
        ];
        
        return view(PR_VIEW_DIR . '/space/add-tag', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница изменение тега пространства
    public function editTagForm()
    {
        $uid            = Base::getUid();
        $slug           = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $space = SpaceModel::getSpaceInfo($slug);

        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 

        $tag = SpaceModel::getTagInfo($space_tags_id);
        Base::PageError404($tag);

        $data = [
            'h1'            => lang('Edit tag'),
            'sheet'         => 'edit-tag', 
            'meta_title'    => lang('Edit tag'),
        ];

        return view(PR_VIEW_DIR . '/space/edit-tag', ['data' => $data, 'uid' => $uid, 'tag' => $tag, 'space' => $space]);
    }
    
    // Изменяем тег пространства
    public function editTag()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $tag_id     = \Request::getPostInt('tag_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Проверка доступа 
        Base::accessСheck($space, 'space', $uid); 

        $redirect = '/s/' . $space['space_slug'] . '/' . $tag_id . '/edit';
        Base::Limits($st_title, lang('titles'), '4', '20', $redirect);
        Base::Limits($st_desc, lang('descriptions'), '30', '180', $redirect);
    
        SpaceModel::tagEdit($tag_id, $st_title, $st_desc);

        Base::addMsg(lang('tags-edit-yes'), 'success');
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
    public function addTag() 
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        // Покажем 404
        $space = SpaceModel::getSpaceId($space_id);
        Base::PageError404($space);
        
        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $redirect = '/space/' . $space['space_slug'] . '/tags/add';
        Base::Limits($st_title, lang('titles'), '4', '20', $redirect);
        Base::Limits($st_desc, lang('descriptions'), '30', '180', $redirect);

        // Добавим
        SpaceModel::tagAdd($space['space_id'], $st_title, $st_desc);
        
        Base::addMsg(lang('tags-add-yes'), 'success');
        redirect('/s/' . $space['space_slug']);
    }

}
