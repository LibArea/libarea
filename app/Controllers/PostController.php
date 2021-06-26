<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\LinkModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\NotificationsModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;
use UrlRecord;
use SimpleImage;
use URLScraper;

class PostController extends \MainController
{
    // Главная страница
    public function index($sheet) 
    {
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        $uid    = Base::getUid();

        $space_user  = SpaceModel::getSpaceUserSigned($uid['id']);
        
        $pagesCount = PostModel::postsFeedCount($space_user, $uid['trust_level'], $uid['id'], $sheet); 
        $posts      = PostModel::postsFeed($page, $space_user, $uid['trust_level'], $uid['id'], $sheet);

        Base::PageError404($posts);

        $result = Array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }  

        // Последние комментарии и подписанные пространства
        $latest_answers     = AnswerModel::latestAnswers($uid);
        $space_signed_bar   = SpaceModel::getSpaceUserSigned($uid['id']);
 
        $result_comm = Array();
        foreach ($latest_answers as $ind => $row) {
            $row['answer_content']      = Base::cutWords($row['answer_content'], 81);
            $row['answer_date']         = lang_date($row['answer_date']);
            $result_comm[$ind]          = $row;
        }

        if($page > 1) { 
            $num = ' | ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }

        if($sheet == 'feed') {
            $meta_title = Config::get(Config::PARAM_HOME_TITLE) . $num;
            $meta_desc  = Config::get(Config::PARAM_META_DESC) . $num;
            $canonical  = Config::get(Config::PARAM_URL);
        } else {
            $meta_title = lang('TOP') .'. '. Config::get(Config::PARAM_HOME_TITLE) . $num;
            $meta_desc  = lang('top-desc') . $num;   
            $canonical  = Config::get(Config::PARAM_URL) . '/top';
        }

        $data = [
            'latest_answers'    => $result_comm,
            'pagesCount'        => $pagesCount,
            'pNum'              => $page,
            'sheet'             => $sheet,
            'canonical'         => $canonical,
            'img'               => Config::get(Config::PARAM_URL) . '/assets/images/areadev.webp',
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
        ];

        return view(PR_VIEW_DIR . '/home', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_bar' => $space_signed_bar]);
    }

    // Полный пост
    public function viewPost()
    {
        $uid        = Base::getUid();
        $slug       = \Request::get('slug');
        $post_id    = \Request::getInt('id');
        
        $post_new   = PostModel::postId($post_id); 

        // Проверим (id, slug)
        Base::PageError404($post_new);
        if($slug != $post_new['post_slug']) {
            redirect('/post/' . $post_new['post_id'] . '/' . $post_new['post_slug']);
        }

        $post = PostModel::postSlug($slug, $uid['id'], $uid['trust_level']); 
        Base::PageError404($post);

        // Редирект для слияния
        if($post['post_merged_id'] > 0) {
            redirect('/post/' . $post['post_merged_id']);
        }

        // Просмотры поста
        if (!isset($_SESSION['pagenumbers'])) {
            $_SESSION['pagenumbers'] = array();
        }

        if (!isset($_SESSION['pagenumbers'][$post['post_id']])) {
            PostModel::postHits($post['post_id']); 
            $_SESSION['pagenumbers'][$post['post_id']] = $post['post_id'];
        }
        
        // Рекомендованные посты
        $recommend = PostModel::postsSimilar($post['post_id'], $post['post_space_id'], $uid['id'], 5);
     
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        if($post['post_date'] != $post['edit_date']) {
            $post['edit_date'] = $post['edit_date'];
        } else {
            $post['edit_date'] = null;
        }
        
        // Покажем черновик только автору
        if($post['post_draft'] == 1 && $post['post_user_id'] != $uid['id']) {
            redirect('/');
        }
     
        $post['post_content']   = Content::text($post['post_content'], 'text');
        $post['post_date_lang'] = lang_date($post['post_date']);
        $post['num_answers']    = word_form($post['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
        
        // общее количество (для модели - беседа)
        $comment_n = $post['post_comments_num'] + $post['post_answers_num'];
        $post['num_comments']   = word_form($comment_n, lang('Comment'), lang('Comments-m'), lang('Comments'));
        
        $post['favorite_post']  = PostModel::getMyPostFavorite($post['post_id'], $uid['id']);
      
        // Получим ответы
        // post_type: 0 - дискуссия, 1 - Q&A
        $post_answers = AnswerModel::getAnswersPost($post['post_id'], $uid['id'], $post['post_type']);
  
        // Получим ЛО (временно)
        // Возможно нам стоит просто поднять ответ на первое место?
        // Изменив порядок сортировки при выбора LO, что позволит удрать это
        if($post['post_lo'] > 0) {
            $lo = AnswerModel::getAnswerLo($post['post_id']);
            $lo['answer_content'] = $lo['answer_content'];
        } else {
            $lo = null;
        }

        $answers = Array();
        foreach ($post_answers as $ind => $row) {
            
            if(strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }

            $row['comm']            = CommentModel::getCommentsAnswer($row['answer_id'], $uid['id']);
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['answer_date']     = lang_date($row['answer_date']);
            $answers[$ind]          = $row;
        }

        if($post['post_content_img']) {
            $content_img  = Config::get(Config::PARAM_URL) . '/uploads/posts/' . $post['post_content_img'];
        } else {
            $content_img  = null;
        }
       
        // title, description
        $desc  = mb_strcut(strip_tags($post['post_content']), 0, 180);
        $meta_desc = $desc . ' — ' . $post['space_name'];
        $meta_title = strip_tags($post['post_title']) . ' — ' . strip_tags($post['space_name']) .' | '. Config::get(Config::PARAM_NAME);

        if($uid['id'] > 0) {
            Request::getResources()->addBottomStyles('/assets/md/editor.css');  
            Request::getResources()->addBottomScript('/assets/md/Markdown.Converter.js'); 
            Request::getResources()->addBottomScript('/assets/md/Markdown.Sanitizer.js');
            Request::getResources()->addBottomScript('/assets/md/Markdown.Editor.js');
            Request::getResources()->addBottomScript('/assets/md/editor.js');
        }
        Request::getResources()->addBottomStyles('/assets/css/magnific-popup.css');
        Request::getResources()->addBottomScript('/assets/js/shares.js');
        Request::getResources()->addBottomScript('/assets/js/jquery.magnific-popup.min.js');
        
        if($post['post_is_delete'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }
        
        // + для Q&A другая разметка (в планах)
        $sheet = 'article';
        
        if($post['post_related']) {
            $post_related = PostModel::postRelated($post['post_related']);
        }
        $post_related = empty($post_related) ? '' : $post_related;

        $data = [
            'h1'            => lang('Post'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/post/' . $post['post_id'] . '/' . $post['post_slug'],
            'sheet'         => $sheet,
            'post_date'     => $post['post_date'],
            'img'           => $content_img,
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
        ];
        
        return view(PR_VIEW_DIR . '/post/post-view', ['data' => $data, 'post' => $post, 'answers' => $answers,  'uid' => $uid,  'recommend' => $recommend,  'lo' => $lo, 'post_related' => $post_related]);
    }

    // Посты участника
    public function userPosts()
    {
        $uid        = Base::getUid();
        $login      = \Request::get('login');
        
        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        Base::PageError404($user);
        
        $posts_user  = PostModel::userPosts($login, $uid['id']); 
        
        $result = Array();
        foreach ($posts_user as $ind => $row) {
            $row['post_date']   = lang_date($row['post_date']);
            $result[$ind]       = $row;
        }

        $meta_title = lang('Posts') . ' ' . $login;
        $meta_desc  = lang('Participant posts') . ' ' . $login;

        $data = [
            'h1'            => $meta_title,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/posts',
            'sheet'         => 'user-post',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
        ]; 
        
        return view(PR_VIEW_DIR . '/post/post-user', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
    
    // Форма добавление поста
    public function addPost() 
    {
        $uid        = Base::getUid();
        $space      = SpaceModel::getSpaceSelect($uid['id'], $uid['trust_level']);
        
        $space_id   = \Request::getInt('space_id');
        
        $data = [
            'h1'            => lang('Add post'),
            'sheet'         => 'add-post',
            'meta_title'    => lang('Add post'),
        ];  
        
        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomStyles('/assets/md/editor.css');  
        Request::getResources()->addBottomScript('/assets/md/Markdown.Converter.js'); 
        Request::getResources()->addBottomScript('/assets/md/Markdown.Sanitizer.js');
        Request::getResources()->addBottomScript('/assets/md/Markdown.Editor.js');
        Request::getResources()->addBottomScript('/assets/md/editor.js');
        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 

        if($uid['trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        } 

        return view(PR_VIEW_DIR . '/post/post-add', ['data' => $data, 'uid' => $uid, 'space' => $space, 'space_id' => $space_id]);
    }
    
    // Добавление поста
    public function create()
    {
        // Получаем title и содержание
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_url               = \Request::getPost('post_url');
        $post_closed            = \Request::getPostInt('closed');
        $post_draft             = \Request::getPostInt('post_draft');
        $post_top               = \Request::getPostInt('top'); 
        $post_type              = \Request::getPostInt('post_type');
        $post_translation       = \Request::getPostInt('translation');
        $post_merged_id         = \Request::getPostInt('post_merged_id');
        $post_tl                = \Request::getPostInt('post_tl');
      
        $related = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related = empty($related) ? '' : implode(',', $related);
      
        // Используем для возврата
        $redirect = '/post/add';

        // Данные кто добавляет
        $uid            = Base::getUid();
        $post_ip_int    = \Request::getRemoteAddress();
        
        // Получаем id пространства
        $space_id   = \Request::getPostInt('space_id');
        $tag_id     = \Request::getPostInt('tag_id');

        // Получаем информацию по пространству
        $space = SpaceModel::getSpaceId($space_id);
        if (!$space) {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
               Base::addMsg(lang('You dont have access'), 'error');
               redirect($redirect);
            }
        }
            
        Base::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '25000', $redirect);
        
        if($post_url) { 
            // Поскольку это для поста, то получим превью 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host']; 
            $link_url           = $parse['scheme'] . '://' . $parse['host'];

            // Мы должны добавить домен, который появился в системе
            // Далее мы можем менять ему статус, запрещать и т.д.
            $link = LinkModel::getLinkOne($post_url_domain);
            if(!$link) {
                // Запишем минимальные данный для дальнешей работы
                $data = [
                    'link_url'          => $link_url,
                    'link_url_domain'   => $post_url_domain,
                    'link_user_id'      => $uid['id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                    'link_cat_id'       => 1,
                ];
                LinkModel::addLink($data);
            } else {
                LinkModel::addLinkCount($post_url_domain);
            }
        }   

        // images
        $name     = $_FILES['images']['name'][0];
        if($name) {
            // Проверка ширину
            $width_h  = getimagesize($_FILES['images']['tmp_name'][0]);
            if ($width_h['0'] < 500) {
                $valid = false;
                Base::addMsg('Ширина меньше 600 пикселей', 'error');
                redirect($redirect);
            }

            $image = new  SimpleImage();
            $path = HLEB_PUBLIC_DIR. '/uploads/posts/';
            $year = date('Y') . '/';
            $file = $_FILES['images']['tmp_name'][0];
            $filename = 'c-' . time();
           
            if(!is_dir($path . $year)) { @mkdir($path . $year); }             
            
            // https://github.com/claviska/SimpleImage
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(820, null)
                ->toFile($path . $year . $filename .'.webp', 'image/webp');
          
            $post_img = $year . $filename . '.webp';
        }
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url           = empty($post_url) ? '' : $post_url;
        $post_url_domain    = empty($post_url_domain) ? '' : $post_url_domain;
        $post_content_img   = empty($post_img) ? '' : $post_img;
        $og_img             = empty($og_img) ? '' : $og_img;
        $tag_id             = empty($tag_id) ? 0 : $tag_id;

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ответов
        if($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_POST)) {
            $num_post =  PostModel::getPostSpeed($uid['id']);
            if(count($num_post) > 2) {
                Base::addMsg(lang('limit-post-day'), 'error');
                redirect('/');
            }
        }
        
        // Получаем SEO поста
        $slugGenerator  = new UrlRecord();
        $slug           = $slugGenerator->GetSeoFriendlyName($post_title); 
        $post_slug      = substr($slug, 0, 90);

        $data = [
            'post_title'            => $post_title,
            'post_content'          => $post_content,
            'post_content_img'      => $post_content_img,
            'post_thumb_img'        => $og_img,
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
            'post_slug'             => $post_slug,
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_draft'            => $post_draft,
            'post_ip_int'           => $post_ip_int,
            'post_user_id'          => $uid['id'],
            'post_space_id'         => $space_id,
            'post_tag_id'           => $tag_id,
            'post_url'              => $post_url,
            'post_url_domain'       => $post_url_domain,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];
        
        // Записываем пост
        $post_id = PostModel::AddPost($data);
        
        // url поста
        $url = '/post/'. $post_id .'/'. $post_slug;
        
        // Уведомление (@login)
        if ($message = Content::parseUser($post_content, true, true)) {
            
			foreach ($message as $user_id) {
                // Запретим отправку себе
				if ($user_id == $post_user_id) {
					continue;
				}
 				$type = 10; // Упоминания в посте      
                NotificationsModel::send($post_user_id, $user_id, $type, $post_id, $url, 1);
			}
		}
        
        // Отправим в Discord
        if(Config::get(Config::PARAM_DISCORD) == 1) {
            if($post_tl == 0 && $post_draft == 0) {
                Base::AddWebhook($post_content, $post_title, $url);
            }
        }
        
        redirect('/');   
    }
    
    // Парсинг
    public function grabMeta() 
    {
        $url    = \Request::getPost('uri');
        $result = URLScraper::get($url); 
        
        return json_encode($result);
    }
    
    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url) 
    {
        $result = URLScraper::get($post_url); 
        if($result['image']) {
            $image = $result['image'];
        } else {
            $image = $result['tags_og']['image'];
        }
        
        if($image) {
            
            $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
            if(in_array($ext, array ('jpg', 'jpeg', 'png'))) {
                
                $path = HLEB_PUBLIC_DIR . '/uploads/posts/thumbnails/';
                $year = date('Y') . '/';
                $filename = 'p-' . time() . '.' . $ext;
                $file = 'p-' . time();
                
                if(!is_dir($path . $year)) { @mkdir($path . $year); }
                $local = $path . $year . $filename;
 
                if (!file_exists($local)){  
                    copy($image, $local); 
                } 
 
                $image = new SimpleImage();
                $image
                ->fromFile($local)  // load image.jpg
                ->autoOrient()      // adjust orientation based on exif data
                ->resize(165, null) 
                ->toFile($path . $year . $file .'.webp', 'image/webp');
 
                if(file_exists($local)) {
                    unlink($local);
                    return $year . $file .'.webp';
                }
                
            }

        }

        return false;
    }
    
    // Показ формы поста для редактирование
    public function editPostForm() 
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();
        
        $post   = PostModel::postId($post_id); 
         
        // Проверка доступа 
        Base::accessСheck($post, 'post', $uid);        
        
        $space = SpaceModel::getSpaceSelect($uid['id'], $uid['trust_level']);
        $tags  = SpaceModel::getSpaceTags($post['post_space_id']);
        $user  = UserModel::getUserId($post['post_user_id']);

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 
        Request::getResources()->addBottomStyles('/assets/md/editor.css');  
        
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomScript('/assets/md/Markdown.Converter.js'); 
        Request::getResources()->addBottomScript('/assets/md/Markdown.Sanitizer.js');
        Request::getResources()->addBottomScript('/assets/md/Markdown.Editor.js');
        Request::getResources()->addBottomScript('/assets/md/editor.js');

        if($uid['trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        } 

        $post_related = PostModel::postRelated($post['post_related']);

        $data = [
            'h1'            => lang('Edit post'),
            'sheet'         => 'edit-post',
            'meta_title'    => lang('Edit post'),
        ];

        return view(PR_VIEW_DIR . '/post/post-edit', ['data' => $data, 'uid' => $uid, 'post' => $post, 'post_related' => $post_related, 'space' => $space, 'tags' => $tags, 'user' => $user]);
    }
    
    // Связанные посты и выбор автора
    public function select($sheet)
    {   
        $search =  \Request::getPost('searchTerm');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        
        if($sheet == 'posts') {
            return PostModel::getSearchPosts($search);
        } 
        
        return UserModel::getSearchUsers($search);
    }
    
    // Изменяем пост
    public function edit() 
    {
        $post_id                = \Request::getPostInt('post_id');
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем 
        $post_type              = \Request::getPostInt('post_type');
        $post_translation       = \Request::getPostInt('translation');
        $post_draft             = \Request::getPostInt('post_draft');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top');
        $post_space_id          = \Request::getPostInt('space_id');
        $post_tag_id            = \Request::getPostInt('tag_id');
        $draft                  = \Request::getPost('draft');
        $post_user_new          = \Request::getPost('post_user_new');
        $post_merged_id         = \Request::getPostInt('post_merged_id');
        $post_tl                = \Request::getPostInt('post_tl');
        
        $uid    = Base::getUid();
        
        $related = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related = empty($related) ? '' : implode(',', $related);
       
        $post = PostModel::postId($post_id); 
         
        $redirect = '/post/edit/' . $post_id; 
         
        // Проверка доступа 
        Base::accessСheck($post, 'post', $uid); 
        
        // Получаем информацию по пространству
        $space = SpaceModel::getSpaceId($post_space_id);
        if (!$space) {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
               Base::addMsg(lang('You dont have access'), 'error');
               redirect($redirect);
            }
        }
        
        // Если есть смена post_user_id и это TL5
        if($post['post_user_id'] != $post_user_new) {
            if($uid['trust_level'] != 5) {
                $post_user_id = $post['post_user_id'];
            } else {    
                $post_user_id = $post_user_new;
            }
        } else {
            $post_user_id = $post['post_user_id'];
        }
        
        Base::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '25000', $redirect);
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных
        // При изменение url считаем частоту смену url после добавления у конкретного пользователя
        // Если больше N оповещение персонала, если изменен на запрещенный, скрытие поста,
        // или более расширенное поведение, а пока просто проверим
        $post_tag_img = empty($post_tag_id) ? '' : $post_tag_id;

        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        // $draft = 1 // это черновик
        // $post_draft = 0 // изменили
        if($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        } else {
            $post_date = $post['post_date'];
        }
        
        // images
        $name = $_FILES['images']['name'][0];
        if($name) { 
            // Проверка ширину
            $width_h  = getimagesize($_FILES['images']['tmp_name'][0]);
            if ($width_h['0'] < 500) {
                $valid = false;
                Base::addMsg('Ширина меньше 600 пикселей', 'error');
                redirect($redirect);
            }

            $image = new  SimpleImage();
            $path = HLEB_PUBLIC_DIR. '/uploads/posts/';
            $year = date('Y') . '/';
            $file = $_FILES['images']['tmp_name'][0];
            $filename = 'c-' . time();
           
            if(!is_dir($path . $year)) { @mkdir($path . $year); }             
            
            // https://github.com/claviska/SimpleImage
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(820, null)
                ->toFile($path . $year . $filename .'.webp', 'image/webp');
          
            $post_img = $year . $filename . '.webp';
            
            // Удалим если есть старая
            if($post['post_content_img'] != $post_img){
                chmod($path . $post['post_content_img'], 0777);
                unlink($path . $post['post_content_img']);
            } 
        }
        
        $post_img = empty($post_img) ? $post['post_content_img'] : $post_img;
        $post_img = empty($post_img) ? '' : $post_img;

        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title, 
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_date'             => $post_date,
            'post_user_id'          => $post_user_id,
            'post_draft'            => $post_draft,
            'post_content'          => $post_content,
            'post_content_img'      => $post_img,
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
            'post_space_id'         => $post_space_id,
            'post_tag_id'           => $post_tag_id,
        ];
        
        // Think through the method 
        // $url = Base::estimationUrl($post_content);

        // Перезапишем пост
        PostModel::editPost($data);
        
        redirect('/post/' . $post['post_id'] . '/' . $post['post_slug']);
    }
    
    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();
        
        $post = PostModel::postId($post_id); 
         
        // Проверка доступа 
        Base::accessСheck($post, 'post', $uid); 
        
        $path_img = HLEB_PUBLIC_DIR. '/uploads/posts/' . $post['post_content_img'];

        PostModel::setPostImgRemove($post['post_id']);
        unlink($path_img);

        Base::addMsg(lang('Cover removed'), 'success');
        redirect('/post/edit/' . $post['post_id']);
    }
    
    // Размещение своего поста у себя в профиле
    public function addPostProfile()
    {
        $uid     = Base::getUid();
        $post_id = \Request::getPostInt('post_id');
        
        $post = PostModel::postId($post_id); 
        
        // Проверка доступа 
        Base::accessСheck($post, 'post', $uid); 
        
        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        PostModel::addPostProfile($post_id, $uid['id']);
       
        return true;
    }
  
    // Помещаем пост в закладки
    public function addPostFavorite()
    {
        $uid     = Base::getUid();
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::postId($post_id); 
        
        if(!$post){
            redirect('/');
        }
        
        PostModel::setPostFavorite($post_id, $uid['id']);
       
        return true;
    }
  
    // Удаляем пост / + восстанавливаем пост
    public function deletePost()
    {
        // Доступ только персоналу
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $post_id = \Request::getPostInt('post_id');
        
        PostModel::PostDelete($post_id);
       
        return true;
    }
    
    // Просмотр поста с титульной страницы
    public function shownPost() 
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::postId($post_id); 
        
        if(!$post){
            return false;
        }
        
        $post['post_content'] = Content::text($post['post_content'], 'text');

        return view(PR_VIEW_DIR . '/post/postcode', ['post' => $post]);
    }
}