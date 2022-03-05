<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{ActionModel, PostModel};
use UserData;

class ActionController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'comment', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($content_id, $type);
        if (!accessСheck($info_type, $type, $this->user, 1, 30)) {
            redirect('/');
        }

        switch ($type) {
            case 'post':
                $url  = getUrlByName('post', ['id' => $info_type['post_id'], 'slug' => $info_type['post_slug']]);
                break;
            case 'comment':
                $post = PostModel::getPost($info_type['comment_post_id'], 'id', $this->user);
                $url  = getUrlByName('post', ['id' => $info_type['comment_post_id'], 'slug' => $post['post_slug']]) . '#comment_' . $info_type['comment_id'];
                break;
            case 'answer':
                $post = PostModel::getPost($info_type['answer_post_id'], 'id', $this->user);
                $url  = getUrlByName('post', ['id' => $info_type['answer_post_id'], 'slug' => $post['post_slug']]) . '#answer_' . $info_type['answer_id'];
                break;
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $log_action_name = $info_type[$type . '_is_deleted'] == 1 ? 'content.restored' : 'content.deleted';
        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $info_type[$type . '_id'] ?? 0,
                'type_content'  => $type,
                'action_name'   => $log_action_name,
                'url_content'   => $url,
            ]
        );

        return true;
    }

    // Связанные посты и выбор автора
    public function select()
    {
        $type           = Request::get('type');
        $search         = Request::getPost('q');
        $search         = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        
        $allowed = ['post', 'user', 'blog',  'section', 'category', 'topic'];
        if (!in_array($type, $allowed)) return false;
        
        return ActionModel::getSearch($search, $type);
    }
}
