<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\SpaceModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\VotesPostModel;
use Base;
use Parsedown;
use UrlRecord;

class PostController extends \MainController
{
    // Главная страница
    public function index() {

        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        $uid    = Base::getUid();

        $space_user  = SpaceModel::getSpaceUser($uid['id']);
        
        $pagesCount = PostModel::getPostHomeCount($space_user); 
        $posts      = PostModel::getPostHome($page, $space_user, $uid['trust_level'], $uid['id']);

        if (!$posts) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $result = Array();
        foreach($posts as $ind => $row){
            
            if(Base::getStrlen($row['post_url']) > 6) {
                $parse = parse_url($row['post_url']);
                $row['post_url'] = $parse['host'];  
            } 
            
            $row['num_answers']         = Base::ru_num('answ', $row['post_answers_num']);
            $row['post_date']           = Base::ru_date($row['post_date']);
            $result[$ind]               = $row;
         
        }  
 
        // Последние комментарии и отписанные пространства
        $latest_answers = AnswerModel::latestAnswers($uid['id']);
        $space_hide      = SpaceModel::getSpaceUser($uid['id']);

        $result_comm = Array();
        foreach($latest_answers as $ind => $row){
            $row['answer_content']    = htmlspecialchars(mb_substr($row['answer_content'],0,81, 'utf-8'));  
            $row['answer_date']       = Base::ru_date($row['answer_date']);
            $result_comm[$ind]         = $row;
        }

        if($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }

        $data = [
            'title'             => lang('Home') . 'Главная | ' . $GLOBALS['conf']['sitename'] . $num, 
            'description'       => lang('home-desc') . ' ' . $GLOBALS['conf']['sitename'] . $num,
            'space_hide'        => $space_hide,
            'latest_answers'    => $result_comm,
            'pagesCount'        => $pagesCount,
            'pNum'              => $page,
        ];

        return view(PR_VIEW_DIR . '/home', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }

    // Посты с начальными не нулевыми голосами, с голосованием, например, от 5
    public function topPost() { 
    
        $account   = \Request::getSession('account');
        $user_id = (!$account) ? 0 : $account['user_id'];
    
        // Пока Top - по количеству комментариев  
        $posts = PostModel::getPostTop($user_id);
 
        $result = Array();
        foreach($posts as $ind => $row){
            $row['num_comments']  = Base::ru_num('comm', $row['post_comments']);
            $row['post_date']     = Base::ru_date($row['post_date']);
            $result[$ind]         = $row;
        }  
        
        // Последние комментарии
        $latest_comments = CommentModel::latestComments($user_id);
        
        $result_comm = Array();
        foreach($latest_comments as $ind => $row){
            $row['comment_content']    = htmlspecialchars(mb_substr($row['comment_content'],0,81, 'utf-8'));  
            $row['comment_date']       = Base::ru_date($row['comment_date']);
            $result_comm[$ind]         = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'            => 'Популярные посты | ' . $GLOBALS['conf']['sitename'], 
            'description'      => 'Самые популярные посты сообществе. ' . $GLOBALS['conf']['sitename'],
            'latest_comments'  => 0,
            'space_hide'       => 0,
            'latest_comments'  => $result_comm,
            'pagesCount'       => 0,
        ];

        return view(PR_VIEW_DIR . '/home', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }

    // Полный пост
    public function viewPost()
    {
        if(!empty($_SESSION['account']['user_id'])) {
            $uid   = $_SESSION['account']['user_id'];
        } else {
            $uid   = 0;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        // Получим пост по slug
        $slug = \Request::get('slug');
        
       // $post = []; 
        $post = PostModel::getPost($slug, $uid);

        // Если нет поста
        if (empty($post))
        {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        // Рекомендованные посты
        $recommend = PostModel::PostsSimilar($post['post_id'], $post['post_space_id'], $uid);
     
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        if($post['post_date'] != $post['edit_date']) {
            $post['edit_date'] = $post['edit_date'];
        } else {
            $post['edit_date'] = null;
        }
        
        if(Base::getStrlen($post['post_url']) > 6) {
            $post['post_url_full'] = $post['post_url'];
            $parse = parse_url($post['post_url']);
            $post['post_url'] = $parse['host'];  
        } else {
            $post['post_url_full'] = null;
        }
        
        // Обработает некоторые поля
        $post['content']         = $Parsedown->text($post['post_content']);
        $post['post_url']       = $post['post_url'];
        $post['post_url_full']  = $post['post_url_full'];
        $post['post_date']      = Base::ru_date($post['post_date']);
        $post['edit_date']      = $post['edit_date'];
        $post['num_answers']    = Base::ru_num('answ', $post['post_answers_num']); 
        $post['favorite_post']  = PostModel::getMyPostFavorite($post['post_id'], $uid);
        
        // Получим ответы
        $post_answers = AnswerModel::getAnswersPost($post['post_id'], $uid);
  
        // Получим ЛО (временно)
        // Возможно нам стоит просто поднять ответ на первое место?
        // Изменив порядок сортировки при выбора LO, что позволит удрать это
        if($post['post_lo'] > 0) {
            $lo = AnswerModel::getAnswerLo($post['post_id']);
            $lo['answer_content'] = $Parsedown->text($lo['answer_content']);
        } else {
            $lo = null;
        }

        $answers = Array();
        foreach($post_answers as $ind => $row){
            
            if(strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }

            $row['comm']                = CommentModel::getCommentsAnswer($row['answer_id'], $uid);
            $row['content']             = $Parsedown->text($row['answer_content']);
            $row['answer_date']         = Base::ru_date($row['answer_date']);
            $row['after']               = $row['answer_after'];
            $row['del']                 = $row['answer_del'];
            $row['favorite_answ']       = AnswerModel::getMyAnswerFavorite($row['answer_id'], $uid);
            $answers[$ind]              = $row;
         
        }
       
        // Перенести в метод, т.к. некобходимо формировать og:* и т.д.
        $description = htmlspecialchars(substr(strip_tags($post['post_content']), 0, 160));

        $uid  = Base::getUid();
        $data = [
            'title'        => $post['post_title'] . ' | ' . $GLOBALS['conf']['sitename'], 
            'description'  => $description . ' — ' . $GLOBALS['conf']['sitename']
        ]; 
        
        return view(PR_VIEW_DIR . '/post/view', ['data' => $data, 'post' => $post, 'answers' => $answers,  'uid' => $uid,  'recommend' => $recommend,  'lo' => $lo]);
    }

    // Посты участника
    public function userPosts()
    {
        $account    = \Request::getSession('account');
        $user_id    = (!$account) ? 0 : $account['user_id'];
        
        $login = \Request::get('login');
        $post_user  = PostModel::getUsersPosts($login, $user_id); 
        
        // Если нет такого пользователя
        if(!$post_user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $result = Array();
        foreach($post_user as $ind => $row){
            $row['post_date']   = Base::ru_date($row['post_date']);
            $result[$ind]       = $row;
        }
 
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Посты ' . $login, 
            'title'         => 'Посты ' . $login . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Посты участника ' . $login . ' с сообществе ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view(PR_VIEW_DIR . '/post/post-user', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
    
    // Форма добавление поста
    public function addPost() 
    {
        // Будем проверять ограничение на частоту 
        // print_r(PostModel::getPostSpeed(1));
        
        $uid  = Base::getUid();
        
        $space = SpaceModel::getSpaceSelect($uid);
        
        // Ajax выбор тега в зависимости от id пространства
        // В шаблоне post/add.php
        // Что будет учитываться в методе createPost() (добавлено)
        // В методе AddPost() необходимые изменения внесены
        
        $data = [
            'h1'            => 'Добавить пост',
            'title'         => 'Добавить пост' . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница добавления поста',
        ];  
       
        return view(PR_VIEW_DIR . '/post/add', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Добавление поста
    public function createPost()
    {
        // Получаем title и содержание
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_content_preview   = \Request::getPost('content_preview');
        $post_content_img       = \Request::getPost('content_img');
        $post_url               = \Request::getPost('post_url');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top');
     
        // IP адрес и ID кто добавляет
        $post_ip_int  = \Request::getRemoteAddress();
        $post_user_id = $_SESSION['account']['user_id'];
        
        // Получаем id пространства
        $space_id   = \Request::getPost('space_id');
        $tag_id     = \Request::getPost('tag_id');
        
        // Проверяем длину title
        if (Base::getStrlen($post_title) < 6 || Base::getStrlen($post_title) > 260)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 260 знаков', 'error');
            redirect('/post/add');
            return true;
        }
        
        // Проверяем длину тела
        if (Base::getStrlen($post_content) < 6 || Base::getStrlen($post_content) > 3500)
        {
            Base::addMsg('Длина поста должна быть от 6 до 3500 знаков', 'error');
            redirect('/post/add');
            return true;
        }
        
        // Проверяем выбор пространства
        if ($space_id == '')
        {
            Base::addMsg('Выберите пространство', 'error');
            redirect('/post/add');
            return true;
        }
        
        if($post_url) { 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host']; 
        } 

        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url               = empty($post_url) ? '' : $post_url;
        $post_url_domain        = empty($post_url_domain) ? '' : $post_url_domain;
        $post_content_preview   = empty($post_content_preview) ? '' : $post_content_preview;
        $post_content_img       = empty($post_content_img) ? '' : $post_content_img;
        $og_img                 = empty($og_img) ? '' : $og_img;
        $tag_id                 = empty($tag_id) ? 0 : $tag_id;
        
        // Ограничим частоту добавления
        // Добавить условие TL
        $num_post =  PostModel::getPostSpeed($post_user_id);
        if(count($num_post) > 5) {
            Base::addMsg('Вы исчерпали лимит постов на сегодня', 'error');
            redirect('/');
        }
        
        // Получаем SEO поста
        $slugGenerator  = new UrlRecord();
        $slug           = $slugGenerator->GetSeoFriendlyName($post_title); 
        $post_slug      = substr($slug, 0, 90);

        $data = [
            'post_title'            => $post_title,
            'post_content'          => $post_content,
            'post_content_preview'  => $post_content_preview,
            'post_content_img'      => $post_content_img,
            'post_thumb_img'        => $og_img,
            'post_slug'             => $post_slug,
            'post_ip_int'           => $post_ip_int,
            'post_user_id'          => $post_user_id,
            'post_space_id'         => $space_id,
            'post_tag_id'           => $tag_id,
            'post_url'              => $post_url,
            'post_url_domain'       => $post_url_domain,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];
        
        // Записываем пост
        PostModel::AddPost($data);
        
        // Отправим в Discord
        if($GLOBALS['conf']['discord'] == 1) {
            $url = '/posts/'. $post_slug;
            Base::AddWebhook($post_content, $post_title, $url);
        }
        
        redirect('/');   
    }
    
    // Парсим title
    public function grabTitle() 
    {
        $url   = \Request::getPost('uri');
        
        ob_start();
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $getit = curl_exec($curl_handle);
        curl_close($curl_handle);
        ob_end_clean();
        preg_match("/<title>(.*)<\/title>/i", $getit, $matches);
        
        return $matches[1];
    }
    
    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url) 
    {
        // Возможно использовать библиотеку, пока так...
        $site_html = file_get_contents($post_url);
        $matches=null;
        preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', $site_html, $matches);
        
        $ogtags=array();
        for($i=0;$i<count($matches[1]);$i++)
        {
            $ogtags[$matches[1][$i]]=$matches[2][$i];
        }
        
        if($ogtags['og:image']) {
            
            $ext = pathinfo(parse_url($ogtags['og:image'], PHP_URL_PATH), PATHINFO_EXTENSION);
            
            if(in_array($ext, array ('jpg', 'jpeg', 'png'))) {
                
                $puth = HLEB_PUBLIC_DIR . '/uploads/thumbnails/';
                $year = date('Y') . '/';
                $filename = 'p-' . time() . '.' . $ext;
                
                if(!is_dir($puth . $year)) { @mkdir($puth . $year); }
                $local = $puth . $year . $filename;
 
                $fp = fopen ($local, 'w+');
                $ch = curl_init($ogtags['og:image']);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);  

                if(file_exists($local)) {
                    return $year . $filename;
                }
                
            }

        }

        return false;
    }
    
    // Показ формы поста для редактирование
    public function editPost() 
    {
        $post_id    = \Request::get('id');
        $uid        = Base::getUid();
        
        // Получим пост
        $post   = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
 
        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $space = SpaceModel::getSpaceSelect($uid);
        $tags  = SpaceModel::getSpaceTags($post['post_space_id']);

        $data = [
            'h1'            => 'Изменить пост',
            'title'         => 'Измененение поста ' . ' | ' . $GLOBALS['conf']['sitename'], 
            'description'   => 'Изменение поста на ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/post/edit', ['data' => $data, 'uid' => $uid, 'post' => $post, 'space' => $space, 'tags' => $tags]);
    }
    
    // Изменяем пост
    public function editPostRecording() 
    {
        $post_id                = \Request::getPostInt('post_id');
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_content_preview   = \Request::getPost('content_preview');
        $post_content_img       = \Request::getPost('content_img');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top');
        $post_space_id          = \Request::getPostInt('space_id');
        $post_tag_id            = \Request::getPostInt('tag_id');
        $post_url               = \Request::getPost('post_url');
        
        $account = \Request::getSession('account');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
        
        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $account['user_id'] && $account['trust_level'] != 5) {
            redirect('/');
        }
        
        // Проверяем длину title
        if (Base::getStrlen($post_title) < 6 || Base::getStrlen($post_title) > 320)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 320 знаков', 'error');
            redirect('/post/edit' .$post_id);
            return true;
        }
        
        // Проверяем длину тела
        if (Base::getStrlen($post_content) < 6 || Base::getStrlen($post_content) > 3500)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 2520 знаков', 'error');
            redirect('/post/edit/' .$post_id);
            return true;
        }
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных
        // При изменение url считаем частоту смену url после добавления у конкретного пользователя
        // Если больше N оповещение персонала, если изменен на запрещенный, скрытие поста,
        // или более расширенное поведение, а пока просто проверим
        $post_url               = empty($post_url) ? '' : $post_url;
        $post_content_preview   = empty($post_content_preview) ? '' : $post_content_preview;
        $post_content_img       = empty($post_content_img) ? '' : $post_content_img;
        $post_tag_img           = empty($post_tag_id) ? '' : $post_tag_id;
        
        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title, 
            'post_content'          => $post_content,
            'post_content_preview'  => $post_content_preview,
            'post_content_img'      => $post_content_img,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
            'post_space_id'         => $post_space_id,
            'post_tag_id'           => $post_tag_id,
            'post_url'              => $post_url,
        ];
        
        // Перезапишем пост
        PostModel::editPost($data);
        
        redirect('/posts/' . $post['post_slug']);
    }
    
    // Размещение своего поста у себя в профиле
    public function addPostProf()
    {
        $post_id = \Request::getPostInt('post_id');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
        
        // Это делать может только может только автор
        if ($post['post_user_id'] != $_SESSION['account']['user_id']) {
            return true;
        }
        
        PostModel::addPostProfile($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Помещаем пост в закладки
    public function addPostFavorite()
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            redirect('/');
        }
        
        PostModel::setPostFavorite($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Удаляем пост / + восстанавливаем пост
    public function deletePost()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
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
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            return false;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        $post = $Parsedown->text($post['post_content']);

        return view(PR_VIEW_DIR . '/post/postcode', ['post_content' => $post]);
    }
  
}
