<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\ActionModel;
use Translate;

class ActionController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $info       = Request::getPost('info');
        $status     = preg_split('/(@)/', $info);
        $type_id    = (int)$status[0]; // id конткнта
        $type       = $status[1];      // тип контента

        $allowed = ['post', 'comment', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($type_id, $type);
        if (!accessСheck($info_type, $type, $this->uid, 1, 30)) {
            redirect('/');
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $status = 'deleted-' . $type;
        if ($info_type[$type . '_is_deleted'] == 1) {
            $status = 'restored-' . $type;
        }

        if ($type == 'post') {
            $info_post_id = $info_type[$type . '_id'];
        } else {
            $info_post_id = $info_type[$type . '_post_id'];
        }

        $data = [
            'user_id'       => $this->uid['user_id'],
            'user_tl'       => $this->uid['user_trust_level'],
            'created_at'    => date("Y-m-d H:i:s"),
            'post_id'       => $info_post_id,
            'content_id'    => $info_type[$type . '_id'],
            'action'        => $status,
            'reason'        => '',
        ];
        // TODO: It will be replaced with a shared user log
        // ActionModel::logsAdd($data);

        return true;
    }

    // Связанные посты и выбор автора
    public function select()
    {
        $type   = Request::get('type');
        $search = Request::get('q');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);

        return ActionModel::getSearch($search, $type);
    }
}
