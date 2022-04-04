<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{ActionModel, PostModel};
use UserData, Html;

class ActionController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Deleting and restoring content 
    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'comment', 'answer', 'reply'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($content_id, $type);
        if (!Html::accessСheck($info_type, $type, $this->user, 1, 30)) {
            redirect('/');
        }

        switch ($type) {
            case 'post':
                $url  = getUrlByName('post', ['id' => $info_type['post_id'], 'slug' => $info_type['post_slug']]);
                $action_type = 'post';
                break;
            case 'comment':
                $post = PostModel::getPost($info_type['comment_post_id'], 'id', $this->user);
                $url  = getUrlByName('post', ['id' => $info_type['comment_post_id'], 'slug' => $post['post_slug']]) . '#comment_' . $info_type['comment_id'];
                $action_type = 'comment';
                break;
            case 'answer':
                $post = PostModel::getPost($info_type['answer_post_id'], 'id', $this->user);
                $url  = getUrlByName('post', ['id' => $info_type['answer_post_id'], 'slug' => $post['post_slug']]) . '#answer_' . $info_type['answer_id'];
                $action_type = 'answer';
                break;
            case 'reply':
                $url  = '/';
                $action_type = 'reply.web';
                break;
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $log_action_name = $info_type[$type . '_is_deleted'] == 1 ? 'content.restored' : 'content.deleted';
 
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

        if ($type == 'post' || $type == 'page') {
            return (new Post\AddPostController)->index($type);
        } elseif ($type == 'topic' || $type == 'blog' || $type == 'category' || $type == 'section') {
            return (new Facets\AddFacetController)->index($type);
        } else {
            return false;
        }
    }

    // Pages (forms) change content and facets (navigation)
    // Страницы (формы) изменения контента и фасетов (навигации)
    public function edit()
    {
        $type = Request::get('type');

        if ($type == 'post' || $type == 'page') {
            return (new Post\EditPostController)->index($type);
        } elseif ($type == 'topic' || $type == 'blog' || $type == 'category'  || $type == 'section') {
            return (new Facets\EditFacetController)->index($type);
        } elseif ($type == 'answer') {
            return (new Answer\EditAnswerController)->index($type);
        } else {
            return false;
        }
    }

    // Creating Content and Adding Facets (Navigation)
    // Создание контента и добавление фасетов (навигация)
    public function create()
    {
        $type = Request::get('type');

        if ($type == 'post' || $type == 'page') {
            return (new Post\AddPostController)->create($type);
        } elseif ($type == 'topic' || $type == 'blog' || $type == 'category'  || $type == 'section') {
            return (new Facets\AddFacetController)->create($type);
        } elseif ($type == 'answer') {
            return (new Answer\AddAnswerController)->create();
        } elseif ($type == 'comment') {
            return (new Comment\AddCommentController)->create();
        } else {
            return false;
        }
    }

    // Content and facet changes (navigation)
    // Изменения контента и фасетов (навигация)
    public function change()
    {
        $type = Request::get('type');

        if ($type == 'post' || $type == 'page') {
            return (new Post\EditPostController)->edit($type);
        } elseif ($type == 'topic' || $type == 'blog' || $type == 'category'  || $type == 'section') {
            return (new Facets\EditFacetController)->edit($type);
        } elseif ($type == 'answer') {
            return (new Answer\EditAnswerController)->edit();
        } elseif ($type == 'comment') {
            return (new Comment\EditCommentController)->edit();
        } else {
            return false;
        }
    }
}
