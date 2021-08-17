<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\PostModel;
use App\Models\ReportModel;
use Lori\Base;

class ReportController extends \MainController
{
    public function index()
    {
        $uid            = Base::getUid();
        $content_type   = \Request::getPost('type');
        $post_id        = \Request::getPostInt('post_id');
        $content_id     = \Request::getPostInt('content_id');

        if ($uid['user_trust_level'] < 1) {
            return false;
        }

        $post   = PostModel::getPostId($post_id);
        Base::PageError404($post);

        $type_id = 'comment_' . $content_id;
        if ($content_type == 'answer') {
            $type_id = 'answer_' . $content_id;
        }

        $url_report   = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#' . $type_id;

        // Оповещение админу
        $type = 20;     // Система флагов  
        $user_id  = 1;  // админу        
        NotificationsModel::send($uid['user_id'], $user_id, $type, $post_id, $url_report, 1);

        $data = [
            'report_user_id'    => $uid['user_id'],
            'report_type'       => $content_type,
            'report_content_id' => $content_id,
            'report_reason'     => lang('Breaking the rules'),
            'report_url'        => $url_report,
            'report_date'       => date("Y-m-d H:i:s"),
            'report_status'     => 0,
        ];

        ReportModel::send($data);
    }
}
