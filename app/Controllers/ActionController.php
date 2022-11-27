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
        if (Access::author($type, $info_type, 30) == false) {
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

    // Related posts, content author change, facets 
    // Связанные посты, изменение автора контента, фасеты
    public function select()
    {
        $type       = Request::get('type');
        $search     = Request::getPost('q');
        if ($search) {
            $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);
        }

        $allowed = ['post', 'user', 'team', 'blog', 'section', 'category', 'topic'];
        if (!in_array($type, $allowed)) return false;

        return ActionModel::getSearch($search, $type);
    }
}
