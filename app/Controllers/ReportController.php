<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{NotificationsModel, PostModel, ReportModel};
use Config, Translate;

class ReportController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    public function index()
    {
        $content_type   = Request::getPost('type');
        $post_id        = Request::getPostInt('post_id');
        $content_id     = Request::getPostInt('content_id');

        // Ограничим флаги
        if ($this->uid['user_trust_level'] == Config::get('trust-levels.tl_stop_report')) return 1;

        $num_report =  ReportModel::getSpeed($this->uid['user_id']);
        if ($num_report > Config::get('trust-levels.all_stop_report')) return 1;

        $post   = PostModel::getPost($post_id, 'id', $this->uid);
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
                'sender_id'     => $this->uid['user_id'],
                'recipient_id'  => 1,  // admin
                'action_type'   => 20, // Система флагов  
                'url'           => $url,
            ]
        );

        $data = [
            'report_user_id'    => $this->uid['user_id'],
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
