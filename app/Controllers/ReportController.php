<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, PostModel, ReportModel};
use Config, Translate, UserData;

class ReportController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        $content_type   = Request::getPost('type');
        $post_id        = Request::getPostInt('post_id');
        $content_id     = Request::getPostInt('content_id');

        // Ограничим флаги
        if ($this->user['trust_level'] == Config::get('trust-levels.tl_stop_report')) return 1;

        $num_report =  ReportModel::getSpeed($this->user['id']);
        if ($num_report > Config::get('trust-levels.all_stop_report')) return 1;

        $post   = PostModel::getPost($post_id, 'id', $this->user);
        pageError404($post);

        $type_id = 'comment_' . $content_id;
        if ($content_type == 'answer') {
            $type_id = 'answer_' . $content_id;
        }

        $slug   = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $url    = $slug . '#' . $type_id;

        // Оповещение админу
        // Admin notification 
        NotificationsModel::send(
            [
                'notification_sender_id'    => $this->user['id'],
                'notification_recipient_id' => 1,  // admin
                'notification_action_type'  => 20, // Система флагов  
                'notification_url'          => $url,
                'notification_read_flag'    => 0,
            ]
        );

        $data = [
            'report_user_id'    => $this->user['id'],
            'report_type'       => $content_type,
            'report_content_id' => $content_id,
            'report_reason'     => Translate::get('breaking the rules'),
            'report_url'        => $url,
            'report_date'       => date("Y-m-d H:i:s"),
            'report_status'     => 0,
        ];

        ReportModel::send($data);
    }
}
