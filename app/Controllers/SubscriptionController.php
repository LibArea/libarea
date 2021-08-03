<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\SubscriptionModel;
use App\Models\SpaceModel;

class SubscriptionController extends \MainController
{
    public function index()
    {
        $account    = \Request::getSession('account');
        $content_id = \Request::getPostInt('content_id');
        $type       = \Request::get('type');

        $user_id    = $account['user_id'];

        $allowed = ['post', 'space', 'topic'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        if ($content_id <= 0) {
            return false;
        }

        if ($type == 'space') {
            // Запретим, если участник создал пространство
            $space_info    = SpaceModel::getSpace($content_id, 'id');
            if ($space_info['space_user_id'] == $user_id) {
                return false;
            }
        }

        SubscriptionModel::focus($content_id, $user_id, $type);

        return true;
    }
}
