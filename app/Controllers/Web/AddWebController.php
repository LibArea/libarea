<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, TopicModel};
use Base, Validation, Translate;

class AddWebController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма добавление домена
    public function index()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        return view(
            '/web/add',
            [
                'meta'  => meta($m = [], Translate::get('add a website')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet' => 'domains',
                ]
            ]
        );
    }

    public function create()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $link_url           = Request::getPost('link_url');
        $link_title         = Request::getPost('link_title');
        $link_content       = Request::getPost('link_content');

        $parse              = parse_url($link_url);
        $link_url_domain    = $parse['host'];
        $link_url           = $parse['scheme'] . '://' . $parse['host'];

        $redirect = getUrlByName('web');
        $link = WebModel::getLinkOne($link_url_domain, $this->uid['user_id']);
        if ($link) {
            addMsg(Translate::get('the site is already there'), 'error');
            redirect($redirect);
        }

        Validation::Limits($link_title, Translate::get('title'), '24', '250', $redirect);
        Validation::Limits($link_content, Translate::get('description'), '24', '1500', $redirect);

        $topic_fields   = Request::getPost() ?? [];
        $topics         = $topic_fields['topic_select'] ?? [];

        $data = [
            'link_url'          => $link_url,
            'link_url_domain'   => $link_url_domain,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_published'    => 1,
            'link_user_id'      => $this->uid['user_id'],
            'link_type'         => 0,
            'link_status'       => 200,
        ];
   
        $link_topic = WebModel::add($data);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = array($row, $link_topic['link_id']);
            }
            TopicModel::addLinkTopics($arr, $link_topic['link_id']);
        }

        redirect($redirect);
    }
}
