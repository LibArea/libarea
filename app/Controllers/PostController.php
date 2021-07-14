<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\AnswerModel;
use App\Models\TopicModel;
use App\Models\CommentModel;
use App\Models\NotificationsModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;
use Lori\UploadImage;

class PostController extends \MainController
{
    // Полный пост
    public function index()
    {
        $uid        = Base::getUid();
        $slug       = \Request::get('slug');
        $post_id    = \Request::getInt('id');
        
        $post_new   = PostModel::postId($post_id); 

        // Проверим (id, slug)
        Base::PageError404($post_new);
        if ($slug != $post_new['post_slug']) {
            redirect('/post/' . $post_new['post_id'] . '/' . $post_new['post_slug']);
        }

        $post = PostModel::postSlug($slug, $uid['id'], $uid['trust_level']); 
        Base::PageError404($post);

        // Редирект для слияния
        if ($post['post_merged_id'] > 0) {
            redirect('/post/' . $post['post_merged_id']);
        }

        // Просмотры поста
        if (!isset($_SESSION['pagenumbers'])) {
            $_SESSION['pagenumbers'] = array();
        }

        if (!isset($_SESSION['pagenumbers'][$post['post_id']])) {
            PostModel::updateCount($post['post_id'], 'hits'); 
            $_SESSION['pagenumbers'][$post['post_id']] = $post['post_id'];
        }
        
        // Рекомендованные посты
        $recommend = PostModel::postsSimilar($post['post_id'], $post['post_space_id'], $uid['id'], 5);
     
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        $post['modified'] = $post['post_date'] != $post['post_modified'] ? true : false;
       
        $topics = PostModel::getPostTopic($post['post_id']);
        
        // Покажем черновик только автору
        if ($post['post_draft'] == 1 && $post['post_user_id'] != $uid['id']) {
            redirect('/');
        }
     
        $post['post_content']   = Content::text($post['post_content'], 'text');
        $post['post_date_lang'] = lang_date($post['post_date']);
        $post['num_answers']    = word_form($post['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
        
        // общее количество (для модели - беседа)
        $comment_n = $post['post_comments_count'] + $post['post_answers_count'];
        $post['num_comments']   = word_form($comment_n, lang('Comment'), lang('Comments-m'), lang('Comments'));
        
        $post['favorite_post']  = PostModel::getMyPostFavorite($post['post_id'], $uid['id']);
      
        // Получим ответы
        // post_type: 0 - дискуссия, 1 - Q&A
        $post_answers = AnswerModel::getAnswersPost($post['post_id'], $uid['id'], $post['post_type']);
  
        // Получим ЛО (временно)
        // Возможно нам стоит просто поднять ответ на первое место?
        // Изменив порядок сортировки при выбора LO, что позволит удрать это
        $lo = null;
        if ($post['post_lo'] > 0) {
            $lo = AnswerModel::getAnswerLo($post['post_id']);
            $lo['answer_content'] = $lo['answer_content'];
        } 

        $answers = Array();
        foreach ($post_answers as $ind => $row) {
            
            if (strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }

            $row['comm']            = CommentModel::getComments($row['answer_id'], $uid['id']);
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['answer_date']     = lang_date($row['answer_date']);
            $answers[$ind]          = $row;
        }
        
        $content_img  = null;
        if ($post['post_content_img']) {
            $content_img  = Config::get(Config::PARAM_URL) . '/uploads/posts/cover/' . $post['post_content_img'];
        } 
       
        // title, description
        $desc  = mb_strcut(strip_tags($post['post_content']), 0, 180);
        $meta_desc = $desc . ' — ' . $post['space_name'];
        $meta_title = strip_tags($post['post_title']) . ' — ' . strip_tags($post['space_name']) .' | '. Config::get(Config::PARAM_NAME);

        Request::getResources()->addBottomStyles('/assets/css/magnific-popup.css');
        Request::getResources()->addBottomScript('/assets/js/shares.js');
        Request::getResources()->addBottomScript('/assets/js/jquery.magnific-popup.min.js');
        
        if ($post['post_is_deleted'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }
        
        // + для Q&A другая разметка (в планах)
        $sheet = 'article';
        
        if ($post['post_related']) {
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

        return view(PR_VIEW_DIR . '/post/post-view', ['data' => $data, 'post' => $post, 'answers' => $answers,  'uid' => $uid,  'recommend' => $recommend,  'lo' => $lo, 'post_related' => $post_related, 'topics' => $topics]);
    }

    // Посты участника
    public function userPosts()
    {
        $uid        = Base::getUid();
        $login      = \Request::get('login');
        
        // Если нет такого пользователя 
        $user   = UserModel::getUser($login, 'slug');
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
    
    // Показ формы поста для редактирование
    public function editPostForm() 
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();
        
        $post   = PostModel::postId($post_id); 
         
        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }  

        $space = SpaceModel::getSpaceSelect($uid['id'], $uid['trust_level']);
        $user  = UserModel::getUser($post['post_user_id'], 'id');

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/editormd.js');
        Request::getResources()->addBottomScript('/assets/editor/lib/marked.min.js');
        Request::getResources()->addBottomScript('/assets/editor/lib/prettify.min.js');
        Request::getResources()->addBottomScript('/assets/editor/config.js');
        
        if ($uid['trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        } 

        $post_related = PostModel::postRelated($post['post_related']);
        $post_topics  = PostModel::getPostTopic($post['post_id']);
        
        $data = [
            'h1'            => lang('Edit post'),
            'sheet'         => 'edit-post',
            'meta_title'    => lang('Edit post'),
        ];

        return view(PR_VIEW_DIR . '/post/post-edit', ['data' => $data, 'uid' => $uid, 'post' => $post, 'post_related' => $post_related, 'space' => $space, 'user' => $user, 'post_topics' => $post_topics]);
    }
    
    // Связанные посты и выбор автора
    public function select($sheet)
    {   
        $search =  \Request::getPost('searchTerm');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        
        if ($sheet == 'posts') {
            return PostModel::getSearchPosts($search, 'all');
        } elseif ($sheet == 'topics') {
            return TopicModel::getSearchTopics($search, 'all');
        } elseif ($sheet == 'main') {
            return TopicModel::getSearchTopics($search, 'main');
        }             
        
        return UserModel::getSearchUsers($search, 'all');
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
        $draft                  = \Request::getPost('draft');
        $post_user_new          = \Request::getPost('post_user_new');
        $post_merged_id         = \Request::getPostInt('post_merged_id');
        $post_tl                = \Request::getPostInt('post_tl');
        
        $uid    = Base::getUid();
        
        // Связанные посты и темы
        $related        = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related   = empty($related) ? '' : implode(',', $related);
        $topics         = empty($_POST['post_topics']) ? '' : $_POST['post_topics'];

        $post = PostModel::postId($post_id); 
         
        $redirect = '/post/edit/' . $post_id; 
         
        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }  
        
        // Получаем информацию по пространству
        $space = SpaceModel::getSpace($post_space_id, 'id');
        if (!$space) {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if ($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
               Base::addMsg(lang('You dont have access'), 'error');
               redirect($redirect);
            }
        }
        
        // Если есть смена post_user_id и это TL5
        if ($post['post_user_id'] != $post_user_new) {
            if ($uid['trust_level'] != 5) {
                $post_user_id = $post['post_user_id'];
            } else {    
                $post_user_id = $post_user_new;
            }
        } else {
            $post_user_id = $post['post_user_id'];
        }
        
        Base::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '25000', $redirect);
        
        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        // $draft = 1 // это черновик
        // $post_draft = 0 // изменили
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        } else {
            $post_date = $post['post_date'];
        }
        
        // Обложка поста
        $cover          = $_FILES['images'];
        $check_cover    = $_FILES['images']['name'][0];
        if($check_cover) {
           $post_img = UploadImage::cover_post($cover, $post);
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
            'post_space_id'         => $post_space_id,
            'post_content'          => $post_content,
            'post_content_img'      => $post_img,
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];
        
        // Think through the method 
        // $url = Base::estimationUrl($post_content);

        // Перезапишем пост
        PostModel::editPost($data);
      
        if(!empty($topics)) { 
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = array($row, $post_id);
            }
            TopicModel::addPostTopics($arr, $post_id);
        } 
      
        redirect('/post/' . $post['post_id'] . '/' . $post['post_slug']);
    }
    
    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();
        
        $post = PostModel::postId($post_id); 
         
        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }  
        
        $path_img = HLEB_PUBLIC_DIR. '/uploads/posts/cover/' . $post['post_content_img'];

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
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }  
        
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
        
        if (!$post) {
            redirect('/');
        }
        
        PostModel::setPostFavorite($post_id, $uid['id']);
       
        return true;
    }
  
    // Просмотр поста с титульной страницы
    public function shownPost() 
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::postId($post_id); 
        
        if (!$post) {
            return false;
        }
        
        $post['post_content'] = Content::text($post['post_content'], 'text');

        return view(PR_VIEW_DIR . '/post/postcode', ['post' => $post]);
    }
}