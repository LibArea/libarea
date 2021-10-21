<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, TopicModel};
use Base, Validation;

class EditWebController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма редактирование домена
    public function index()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getLinkId($domain_id);

        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        $meta = meta($m = [], lang('change the site') . ' | ' . $domain['link_url_domain']);
        $data = [
            'domain'        => $domain,
            'sheet'         => 'domains',
            'topic_select'  => WebModel::getLinkTopic($domain['link_id']),
        ];

        return view('/web/edit', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    public function edit()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $redirect   = getUrlByName('web');
        $link_id    = Request::getPostInt('link_id');
        if (!$link  = WebModel::getLinkId($link_id)) {
            redirect($redirect);
        }

        $link_url       = Request::getPost('link_url');
        $link_title     = Request::getPost('link_title');
        $link_content   = Request::getPost('link_content');
        $link_status    = Request::getPostInt('link_status');
        $post_fields    = Request::getPost() ?? [];
        $topics         = $post_fields['topic_select'] ?? [];

        Validation::Limits($link_title, lang('title'), '24', '250', $redirect);
        Validation::Limits($link_content, lang('description'), '24', '1500', $redirect);

        $data = [
            'link_id'           => $link['link_id'],
            'link_url'          => $link_url,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_user_id'      => $this->uid['user_id'],
            'link_type'         => 0,
            'link_status'       => $link_status,
        ];

        WebModel::editLink($data);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = array($row, $link['link_id']);
            }
            TopicModel::addLinkTopics($arr, $link['link_id']);
        }

        redirect($redirect);
    }
}
