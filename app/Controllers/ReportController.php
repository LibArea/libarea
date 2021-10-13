<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, PostModel, ReportModel};
use Base, Config;

class ReportController extends MainController
{
    public function index()
    {
        $uid            = Base::getUid();
        $content_type   = Request::getPost('type');
        $post_id        = Request::getPostInt('post_id');
        $content_id     = Request::getPostInt('content_id');

        // Ограничим флаги
        if ($uid['user_trust_level'] == Config::get('trust-levels.tl_stop_report')) return 1;

        $num_report =  ReportModel::getSpeed($uid['user_id']);
        if ($num_report > Config::get('trust-levels.all_stop_report')) return 1;

        $post   = PostModel::getPostId($post_id);
        Base::PageError404($post);

        $type_id = 'comment_' . $content_id;
        if ($content_type == 'answer') {
            $type_id = 'answer_' . $content_id;
        }

        $slug = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $url_report   = $slug . '#' . $type_id;

        // Оповещение админу
        $type = 20;     // Система флагов  
        $user_id  = 1;  // админу        
        NotificationsModel::send($uid['user_id'], $user_id, $type, $post_id, $url_report, 1);

        $data = [
            'report_user_id'    => $uid['user_id'],
            'report_type'       => $content_type,
            'report_content_id' => $content_id,
            'report_reason'     => lang('breaking the rules'),
            'report_url'        => $url_report,
            'report_date'       => date("Y-m-d H:i:s"),
            'report_status'     => 0,
        ];

        ReportModel::send($data);
    }
}
