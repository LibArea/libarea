<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{ActionModel, PostModel};
use App\Models\User\UserModel;
use Html, Access, UserData;

class ActionController extends Controller
{
    // Deleting and restoring content 
    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'comment', 'answer', 'reply', 'item'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($content_id, $type);
        if (Access::author($type, $info_type[$type . '_user_id'], $info_type[$type . '_date'], 30) == false) {
            redirect('/');
        }

        switch ($type) {
            case 'post':
                $url  = url('post', ['id' => $info_type['post_id'], 'slug' => $info_type['post_slug']]);
                $action_type = 'post';
                break;
            case 'comment':
                $post = PostModel::getPost($info_type['comment_post_id'], 'id', $this->user);
                $url  = url('post', ['id' => $info_type['comment_post_id'], 'slug' => $post['post_slug']]) . '#comment_' . $info_type['comment_id'];
                $action_type = 'comment';
                break;
            case 'answer':
                $post = PostModel::getPost($info_type['answer_post_id'], 'id', $this->user);
                $url  = url('post', ['id' => $info_type['answer_post_id'], 'slug' => $post['post_slug']]) . '#answer_' . $info_type['answer_id'];
                $action_type = 'answer';
                break;
            case 'reply':
                $url  = '/';
                $action_type = 'reply_web';
                break;
            case 'item':
                $url  = url('web.deleted');
                $action_type = 'website';
                break;
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $log_action_name = $info_type[$type . '_is_deleted'] == 1 ? 'content_restored' : 'content_deleted';

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $info_type[$type . '_id'] ?? 0,
                'action_type'   => $action_type,
                'action_name'   => $log_action_name,
                'url_content'   => $url,
            ]
        );

        return true;
    }

    // Related posts, content author change, facets 
    // Связанные посты, изменение автора контента, фасеты
    public function select()
    {
        $type       = Request::get('type');
        $search     = Request::getPost('q');
        if ($search) {
            $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        }

        $allowed = ['post', 'user', 'blog',  'section', 'category', 'topic'];
        if (!in_array($type, $allowed)) return false;

        return ActionModel::getSearch($search, $type);
    }

    // Pages (forms) adding content (facets) 
    // Страницы (формы) добавления контента (фасетов)
    public function add()
    {
        $type = Request::get('type');

        if (in_array($type, ['post', 'page'])) {
           return (new Post\AddPostController)->index($type);
        }

        if (in_array($type, ['topic', 'blog', 'category', 'section'])) {
           return (new Facets\AddFacetController)->index($type);
        }

        return false;
     
    }

    // Pages (forms) change content and facets (navigation)
    // Страницы (формы) изменения контента и фасетов (навигации)
    public function edit()
    {
        $type = Request::get('type');

        if (in_array($type, ['post', 'page'])) {
           return (new Post\EditPostController)->index($type);
        }

        if (in_array($type, ['topic', 'blog', 'category', 'section'])) {
           return (new Facets\EditFacetController)->index($type);
        }
        
        if ($type === 'answer') {
           return (new Answer\EditAnswerController)->index($type);
        }

        return false;
    }

    // Creating Content and Adding Facets (Navigation)
    // Создание контента и добавление фасетов (навигация)
    public function create()
    {
        $this->limitingMode();
        
        $type = Request::get('type');
        
        // TODO: Изменим поля в DB для: 
        if (!in_array($type, ['message', 'item'])) {        
            $this->limitContentDay($type);
        }

        if (in_array($type, ['post', 'page'])) {
           return (new Post\AddPostController)->create($type);
        }

        if (in_array($type, ['topic', 'blog', 'category', 'section'])) {
           return (new Facets\AddFacetController)->create($type);
        }
        
        if ($type === 'answer') {
           return (new Answer\AddAnswerController)->create($type);
        }
        
        if ($type === 'comment') {
           return (new Comment\AddCommentController)->create($type);
        }
        
        if ($type === 'invitation') {
           return (new User\InvitationsController)->create($type);
        }
        
        if ($type === 'message') {
           return (new MessagesController)->create($type);
        }
        
        if ($type === 'folder') {
           return (new FolderController)->create($type);
        }
        
        if ($type === 'team') {
           return (new \Modules\Teams\App\Add)->create($type);
        }

        if ($type === 'reply') {
           return (new \Modules\Catalog\App\Reply)->create($type);
        }

        if ($type === 'item') {
           return (new \Modules\Catalog\App\Add)->create($type);
        }
        
        return false;
    }

    // Content and facet changes (navigation)
    // Изменения контента и фасетов (навигация)
    public function change()
    {
        $this->limitingMode();
        
        $type = Request::get('type');

        if (in_array($type, ['post', 'page'])) {
           return (new Post\EditPostController)->edit($type);
        }

        if (in_array($type, ['topic', 'blog', 'category', 'section'])) {
           return (new Facets\EditFacetController)->edit($type);
        }
        
        if ($type === 'answer') {
           return (new Answer\EditAnswerController)->edit();
        }

        if ($type === 'comment') {
           return (new Comment\EditCommentController)->edit();
        }

        if ($type === 'team') {
           return (new \Modules\Teams\App\Edit)->edit($type);
        }

        if ($type === 'reply') {
           return (new \Modules\Catalog\App\Reply)->edit($type);
        }

        if ($type === 'web') {
           return (new \Modules\Catalog\App\Edit)->edit($type);
        }

        return false;
    }
    
    // Stop changing (adding) content if the user is "frozen"    
    // Остановим изменение (добавление) контента если пользователь "заморожен"
    public function limitingMode()
    {
        if ($this->user['limiting_mode'] == 1) {
            Html::addMsg(__('msg.silent_mode',), 'error');
            redirect('/');
        } 
    }
    
    // Лимит: за сутки для всех TL и лимит за день
    public function limitContentDay($type)
    {
        if (UserData::checkAdmin()) {
            return true;
        }
         
        // Лимит за день для ВСЕХ уровней доверия
        $сount = ActionModel::getSpeedDay($this->user['id'], $type);

        if ($сount >= config('trust-levels.all_limit')) {
            Html::addMsg(__('msg.limit_day', ['tl' => UserData::getUserTl()]), 'error');
            redirect('/');
        }
        
        // Если TL меньше 2 (начальный уровень после регистрации)
        if (UserData::getUserTl() < UserData::USER_SECOND_LEVEL) {
            $сount = ActionModel::allContentUserCount($this->user['id']);
            if ($сount >= config('trust-levels.perDay_' . $type)) {
                Html::addMsg(__('msg.limit_day', ['tl' => UserData::getUserTl()]), 'error');
                redirect('/');
            }
        } 
        
        return true;
    }
    
}
