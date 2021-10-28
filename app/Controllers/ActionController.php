<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\ActionModel;
use Base, Translate;

class ActionController extends MainController
{
    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $uid        = Base::getUid();
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
        if (!accessСheck($info_type, $type, $uid, 1, 30)) {
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
            'user_id'       => $uid['user_id'],
            'user_tl'       => $uid['user_trust_level'],
            'created_at'    => date("Y-m-d H:i:s"),
            'post_id'       => $info_post_id,
            'content_id'    => $info_type[$type . '_id'],
            'action'        => $status,
            'reason'        => '',
        ];

        ActionModel::moderationsAdd($data);

        return true;
    }

    // Журнал логирования удалений / восстановлений контента
    public function moderation()
    {
        $moderations_log    = ActionModel::getModerations();

        $result = array();
        foreach ($moderations_log as $ind => $row) {
            $row['mod_created_at']  = lang_date($row['mod_created_at']);
            $result[$ind]           = $row;
        }

        return view(
            '/moderation/index',
            [
                'data' => [
                    'moderations'   => $result,
                    'sheet'         => 'moderations',
                ],
                'meta' => meta($m = [], Translate::get('moderation log')),
                'uid' => Base::getUid()
            ]
        );
    }

    // Связанные посты и выбор автора
    public function select()
    {
        $type   = Request::get('type');
        $search = Request::getPost('searchTerm');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);

        return ActionModel::getSearch($search, $type);
    }
}
