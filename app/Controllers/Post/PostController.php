<?php

namespace App\Controllers\Post;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\FeedModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\FavoriteModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class PostController extends \MainController
{
    // Полный пост
    public function index()
    {
        $uid        = Base::getUid();
        $slug       = \Request::get('slug');
        $post_id    = \Request::getInt('id');
        
        $post_new   = PostModel::getPostId($post_id); 

        // Проверим (id, slug)
        Base::PageError404($post_new);
        if ($slug != $post_new['post_slug']) {
            redirect('/post/' . $post_new['post_id'] . '/' . $post_new['post_slug']);
        }

        $post = PostModel::getPostSlug($slug, $uid['id'], $uid['trust_level']); 
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
        
        $post['favorite_post']  = FavoriteModel::getUserFavorite($post['post_id'], $uid['id'], 1);
      
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
       
        $desc  = mb_strcut(strip_tags($post['post_content']), 0, 180);
        $meta_desc = $desc . ' — ' . $post['space_name'];
        $meta_title = strip_tags($post['post_title']) . ' — ' . strip_tags($post['space_name']) .' | '. Config::get(Config::PARAM_NAME);

        Request::getResources()->addBottomStyles('/assets/css/magnific-popup.css');
        Request::getResources()->addBottomScript('/assets/js/shares.js');
        Request::getResources()->addBottomScript('/assets/js/jquery.magnific-popup.min.js');
        
        Request::getResources()->addBottomStyles('/assets/css/hint.css');
        Request::getResources()->addBottomScript('/assets/js/hint.js');
  
        if ($post['post_is_deleted'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }
        
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

        return view(PR_VIEW_DIR . '/post/view', ['data' => $data, 'post' => $post, 'answers' => $answers,  'uid' => $uid,  'recommend' => $recommend,  'lo' => $lo, 'post_related' => $post_related, 'topics' => $topics]);
    }

    // Посты участника
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;
        
        // Если нет такого пользователя 
        $user   = UserModel::getUser($login, 'slug');
        Base::PageError404($user);
        
        $limit = 100; 
        $data       = ['post_user_id' => $user['id']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'user', $data);
        $pagesCount = FeedModel::feedCount($uid, 'user', $data);
  
        $result = Array();
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $h1 = lang('Posts') . ' ' . $login;
        $meta_desc  = lang('Participant posts') . ' ' . $login;

        $data = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/posts',
            'sheet'         => 'user-post',
            'h1'            => lang('Posts') . ' ' . $login,
            'meta_title'    => lang('Posts') . ' ' . $login . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => $meta_desc . ' '. Config::get(Config::PARAM_HOME_TITLE),
        ]; 
        
        return view(PR_VIEW_DIR . '/post/post-user', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }

    // Размещение своего поста у себя в профиле
    public function addPostProfile()
    {
        $uid     = Base::getUid();
        $post_id = \Request::getPostInt('post_id');
        
        $post = PostModel::getPostId($post_id); 
        
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
        $post    = PostModel::getPostId($post_id); 
        
        Base::PageRedirection($post);
        
        PostModel::setPostFavorite($post_id, $uid['id']);
       
        return true;
    }
  
    // Просмотр поста с титульной страницы
    public function shownPost() 
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if (!$post) {
            return false;
        }
        
        $post['post_content'] = Content::text($post['post_content'], 'text');

        return view(PR_VIEW_DIR . '/post/postcode', ['post' => $post]);
    }
  
}