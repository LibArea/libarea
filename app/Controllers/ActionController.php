<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{ActionModel, PublicationModel};

class ActionController extends Controller
{
    /**
     * Deleting and restoring content 
     * Удаление и восстановление контента
     */
    public function deletingAndRestoring()
    {
        $content_id = Request::post('content_id')->asInt();
        $type       = Request::post('type')->value();

        $allowed = ['post', 'comment', 'reply', 'item'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Access check 
        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($content_id, $type);

        if ($this->container->access()->author($type, $info_type) == false) {
            redirect('/');
        }

        switch ($type) {
            case 'post':
			case 'note':
			case 'article':
			case 'question':
                $url  = post_slug($type, $info_type['post_id'], $info_type['post_slug']);
                $action_type = $type;
                break;
            case 'comment':
                $post = PublicationModel::getPost($info_type['comment_post_id'], 'id', $this->container->user()->get());
                $url  = post_slug($info_type['post_type'], $info_type['comment_post_id'], $post['post_slug']) . '#comment_' . $info_type['comment_id'];
                $action_type = 'comment';
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
