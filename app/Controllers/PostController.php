<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\SpaceModel;
use App\Models\CommentModel;
use App\Models\VotesCommentModel;
use Base;
use Parsedown;

class PostController extends \MainController
{
    // Главная страница
    public function index() {

        if (Request::get('page')) {
            $page = (int)Request::get('page');
        } else {
            $page = 1;
        }

        if (!$page) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        if($account = Request::getSession('account')){
            // Получаем все теги отписанные участником
            $space_user = SpaceModel::getSpaceUser($account['user_id']);
            $user_id    = $account['user_id'];
        } else {
            $space_user = [];
            $user_id    = 0;
        }
            
        $pagesCount = PostModel::getPostHomeCount($space_user); 
        $posts      = PostModel::getPostHome($page, $space_user);
        
        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']        = $row['avatar'];
            $row['title']         = $row['post_title'];
            $row['slug']          = $row['post_slug']; 
            $row['num_comments']  = $row['post_comments'];            
            $row['post_comments'] = Base::ru_num('comm', $row['post_comments']);
            $row['date']          = Base::ru_date($row['post_date']);
            $result[$ind]         = $row;
         
        }  

        $latest_comments = CommentModel::latestComments();
        
        $space_hide      = SpaceModel::getSpaceUser($user_id);
        
        $result_comm = Array();
        foreach($latest_comments as $ind => $row){
            
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
   
            $row['comment_avatar']     = $row['avatar'];
            $row['comment_content']    = htmlspecialchars(mb_substr($row['comment_content'],0,81, 'utf-8'));  
            $row['comment_date']       = Base::ru_date($row['comment_date']);
            $result_comm[$ind]         = $row;
         
        }

        $data = [
            'title'            => 'Посты - главная страница сайта', 
            'posts'            => $result,
            'space_hide'       => $space_hide,
            'latest_comments'  => $result_comm,
            'msg'              => Base::getMsg(),
            'uid'              => Base::getUid(),
            'pagesCount'       => $pagesCount,
            'pNum'             => $page,
        ];

        return view("home", ['data' => $data]);
    }

    // Все посты
    public function allPost() {

        $posts = PostModel::getPostAll();
 
        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
 
            $row['avatar']        = $row['avatar'];
            $row['title']         = $row['post_title'];
            $row['slug']          = $row['post_slug'];
            $row['num_comments']  = $row['post_comments'];            
            $row['post_comments'] = Base::ru_num('comm', $row['post_comments']);
            $row['date']          = Base::ru_date($row['post_date']);
            $result[$ind]         = $row;
         
        }  

       $data = [
         'title'            => 'Все посты',
         'latest_comments'  => 0,
         'space_hide'       => 0,
         'posts'            => $result,
         'msg'              => Base::getMsg(),
         'uid'              => Base::getUid(),
       ];

        return view("home", ['data' => $data]);
    }

    // Посты с начальными не нулевыми голосами, с голосованием, например, от 5
    public function topPost() {
        // пока Top - по количеству комментариев  
        $posts = PostModel::getPostTop();
 
        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
 
            $row['avatar']        = $row['avatar'];
            $row['title']         = $row['post_title'];
            $row['slug']          = $row['post_slug'];
            $row['num_comments']  = $row['post_comments'];            
            $row['post_comments'] = Base::ru_num('comm', $row['post_comments']);
            $row['date']          = Base::ru_date($row['post_date']);
            $result[$ind]         = $row;
         
        }  

       $data = [
         'title'            => 'Все посты',
         'latest_comments'  => 0,
         'space_hide'       => 0,
         'posts'            => $result,
         'pagesCount'       => 0,
         'msg'              => Base::getMsg(),
         'uid'              => Base::getUid(),
       ];

        return view("home", ['data' => $data]);
    }

    // Полный пост
    public function view()
    {

        $uid = Request::getSession('account') ?? []; 
        if(!empty($uid['user_id'])) {
            $uid   = $uid['user_id'];
        } else {
            $uid   = 0;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        // Получим пост по slug
        $slug = Request::get('slug');
        $post = PostModel::getPost($slug);
 
        // Если нет поста
        if (empty($post))
        {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
       
        if(!$post['avatar']) {
            $post['avatar'] = 'noavatar.png';
        }
  
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        if($post['post_date'] != $post['edit_date']) {
            $post['edit_date'] = $post['edit_date'];
        } else {
            $post['edit_date'] = NULL;
        }
        
        // Перечислим пока все, чтобы знать необходимые...
        $data_post = [
            'id'            => $post['post_id'],
            'title'         => $post['post_title'], 
            'content'       => $Parsedown->text($post['post_content']),
            'post_user_id'  => $post['post_user_id'],
            'date'          => Base::ru_date($post['post_date']),
            'edit_date'     => Base::ru_date($post['edit_date']),
            'slug'          => $post['post_slug'],
            'login'         => $post['login'],
            'avatar'        => $post['avatar'],
            'num_comments'  => $post['post_comments'],   
            'space_tip'     => $post['space_tip'],
            'space_slug'    => $post['space_slug'],
            'space_name'    => $post['space_name'],            
            'post_comments' => Base::ru_num('comm', $post['post_comments']), 
            
        ];
        
        // Получим комментарии
        $comm = CommentModel::getCommentsPost($post['post_id']);
 
        $result = Array();
        foreach($comm as $ind => $row){
            
            if(!$row['avatar'] ) {
                $row['avatar']  = 'noavatar.png';
            } 
 
            $row['comment_on'] = $row['comment_on'];  
            $row['avatar']     = $row['avatar'];
            $row['content']    = $Parsedown->text($row['comment_content']);
            $row['date']       = Base::ru_date($row['comment_date']);
            $row['after']      = $row['comment_after'];
            $row['del']        = $row['comment_del'];
            $row['comm_vote_status'] = VotesCommentModel::getVoteStatus($row['comment_id'], $uid);
            $result[$ind]    = $row;
         
        }
        
        $data = [
         'post'     => $data_post,
         'comments' => $this->buildTree(0, 0, $result),
         'title'    => $data_post['title'],
         'msg'      => Base::getMsg(),
         'uid'      => Base::getUid(),
        ]; 
        
        return view("post/view", ['data' => $data]);
        
    }
    
    // Для дерева комментариев
     private function buildTree($comment_on , $level, $comments, $tree=array()){
        $level++;
        foreach($comments as $comment){
            if ($comment['comment_on'] == $comment_on ){
                $comment['level'] = $level-1;
                $tree[] = $comment;
                $tree = $this->buildTree($comment['comment_id'], $level, $comments, $tree);
            }
        }
		return $tree;
    }
    
    // Посты участника
    public function userPosts()
    {
        
        $login = Request::get('login');

        $posts  = PostModel::getUsersPosts($login); 
        
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
 
            $row['avatar']  = $row['avatar'];
            $row['title']   = $row['post_title'];
            $row['slug']    = $row['post_slug'];
            $row['date']    = Base::ru_date($row['post_date']);
            $result[$ind]   = $row;
         
        }
 
        $data = [
         'posts'    => $result,
         'title'    => 'Посты ' . $login,
         'msg'      => Base::getMsg(),
         'uid'      => Base::getUid(),
        ]; 
        
        return view("post/user", ['data' => $data]);
        
    }
    
    // Форма добавление поста
    public function addPost() 
    {
        
        $data = [
            'title'    => 'Добавить пост',
            'msg'      => Base::getMsg(),
            'uid'      => Base::getUid(),
        ];   
       
        return view("post/add", ['data' => $data]);
       
    }
    
    // Lобавление поста
    public function createPost()
    {
        // Авторизировались или нет
        if (!$account = Request::getSession('account'))
        {
            return false;
        }  
        
        // Получаем title и содержание
        $post_title   = Request::getPost('post_title');
        $post_content = $_POST['post_content']; // не фильтруем
        
        // IP адрес и ID кто добавляет
        $post_ip_int = Request::getRemoteAddress();
        
        // Получаем id тега
        $space_id = (int)Request::getPost('space');
        
        // id того, кто добавляет пост
        $post_user_id = $account['user_id'];
        
        // Проверяем длину title
        if (strlen($post_title) < 6 || strlen($post_title) > 320)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 320 знаков', 'error');
            redirect('/post/add/');
            return true;
        }
        
        // Проверяем длину тела
        if (strlen($post_content) < 6 || strlen($post_content) > 2500)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 2520 знаков', 'error');
            redirect('/post/add/');
            return true;
        }
        
        // Проверяем выбор тега
        if ($space_id == '')
        {
            Base::addMsg('Выберите тег', 'error');
            redirect('/post/add/');
            return true;
        }
        
        // Получаем SEO поста
        $post_slug = Base::seo($post_title); 
        
        // Записываем пост
        PostModel::AddPost($post_title, $post_content, $post_slug, $post_ip_int, $post_user_id, $space_id);
        
        redirect('/');   
    }
    
    // Показ формы поста для редактирование
    public function editPost() 
    {
        if(!$account = Request::getSession('account')) {
           redirect('/');
        } 
        
        $post_id = Request::get('id');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
        
        // Редактировать может только автор
        if ($post['post_user_id'] != $account['user_id']) {
            redirect('/');
        }
        
        $data = [
            'title'      => 'Изменить пост',
            'id'         => $post_id,
            'title_post' => htmlspecialchars($post['post_title']),
            'content'    => $post['post_content'],
            'space_tip'  => $post['space_tip'],
            'space_slug' => $post['space_slug'],
            'space_name' => $post['space_name'],  
            'msg'        => Base::getMsg(),
            'uid'        => Base::getUid(),
        ];
        
        return view("post/edit", ['data' => $data]);
        
    }
    
    // Запись при редактирование
    public function editPostRecording() 
    {
        
        if(!$account = Request::getSession('account')) {
           redirect('/');
        } 
        
        $post_id       = Request::getPost('post_id');
        $post_title    = Request::getPost('post_title');
        $post_content  = Request::getPost('post_content');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
        
        // Редактировать может только автор
        if ($post['post_user_id'] != $account['user_id']) {
            redirect('/');
        }
        
        // Проверяем длину title
        if (strlen($post_title) < 6 || strlen($post_title) > 320)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 320 знаков', 'error');
            redirect('/post/edit/' .$post_id);
            return true;
        }
        
        // Проверяем длину тела
        if (strlen($post_content) < 6 || strlen($post_content) > 2500)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 2520 знаков', 'error');
            redirect('/post/edit/' .$post_id);
            return true;
        }
        
        PostModel::editPost($post_id, $post_title, $post_content);
        
        redirect('/posts/' . $post['post_slug']);
        
    }
    
}
