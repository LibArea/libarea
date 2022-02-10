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
        $info           = Request::getPost('info');
        $status         = preg_split('/(@)/', $info);
        $type_id        = (int)$status[0]; // id конткнта
        $content_type   = $status[1];      // тип контента

        $allowed = ['post', 'comment', 'answer'];
        if (!in_array($content_type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($type_id, $content_type);
        if (!accessСheck($info_type, $content_type, $this->user, 1, 30)) {
            redirect('/');
        }

        switch ($content_type) {
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

        ActionModel::setDeletingAndRestoring($content_type, $info_type[$content_type . '_id'], $info_type[$content_type . '_is_deleted']);

        $log_action_name = $info_type[$content_type . '_is_deleted'] == 1 ? 'content.restored' : 'content.deleted';
        ActionModel::addLogs(
            [
                'log_user_id'       => $this->user['id'],
                'log_user_login'    => $this->user['login'],
                'log_id_content'    => $info_type[$content_type . '_id'] ?? 0,
                'log_type_content'  => $content_type,
                'log_action_name'   => $log_action_name,
                'log_url_content'   => $url,
                'log_date'          => date("Y-m-d H:i:s"),
            ]
        );

        return true;
    }

    // Связанные посты и выбор автора
    public function select()
    {
        $content_type   = Request::get('type');
        $search         = Request::getPost('q');
        $search         = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        return ActionModel::getSearch($search, $content_type);
    }
}
