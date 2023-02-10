<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{ActionModel, PostModel};
use Access;

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

        // Access check 
        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($content_id, $type);
        if (Access::author($type, $info_type) == false) {
            redirect('/');
        }

        switch ($type) {
            case 'post':
                $url  = post_slug($info_type['post_id'], $info_type['post_slug']);
                $action_type = 'post';
                break;
            case 'comment':
                $post = PostModel::getPost($info_type['comment_post_id'], 'id', $this->user);
                $url  = post_slug($info_type['comment_post_id'], $post['post_slug']) . '#comment_' . $info_type['comment_id'];
                $action_type = 'comment';
                break;
            case 'answer':
                $post = PostModel::getPost($info_type['answer_post_id'], 'id', $this->user);
                $url  = post_slug($info_type['answer_post_id'], $post['post_slug']) . '#answer_' . $info_type['answer_id'];
                $action_type = 'answer';
                break;
            case 'reply':
                $url  = '/';
                $action_type = 'reply';
                break;
            case 'item':
                $url  = url('web.deleted');
                $action_type = 'item';
                break;
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $log_action_name = $info_type[$type . '_is_deleted'] == 1 ? 'restored' : 'deleted';

        ActionModel::addLogs(
            [
                'id_content'    => $info_type[$type . '_id'] ?? 0,
                'action_type'   => $action_type,
                'action_name'   => $log_action_name,
                'url_content'   => $url,
            ]
        );

        return true;
    }
}
