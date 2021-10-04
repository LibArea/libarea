<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, TopicModel};
use Agouti\{Base, Content, Validation};

class WebsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $domains    = WebModel::getLinksAll($page, $limit, $this->uid['user_id']);

        $result = array();
        foreach ($domains as $ind => $row) {
            $row['link_content']    = Content::text($row['link_content'], 'line');
            $result[$ind]           = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');
        $meta = [
            'meta_title'    => lang('domains'),
            'sheet'         => 'domains',
        ];

        $data = [
            'sheet'         => $sheet == 'all' ? 'domains' : $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'domains'       => $result,
        ];

        return view('/admin/web/webs', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Форма добавление домена
    public function addPage()
    {
        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        $meta = [
            'meta_title'    => lang('add a website'),
            'sheet'         => 'domains',
        ];

        $data = [
            'sheet'         => 'domains',
        ];

        return view('/admin/web/add', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Добавление домена
    public function add()
    {
        $link_url           = Request::getPost('link_url');
        $link_title         = Request::getPost('link_title');
        $link_content       = Request::getPost('link_content');

        $parse              = parse_url($link_url);
        $link_url_domain    = $parse['host'];
        $link_url           = $parse['scheme'] . '://' . $parse['host'];

        $redirect = '/web';
        $link = WebModel::getLinkOne($link_url_domain, $this->uid['user_id']);
        if ($link) {
            addMsg(lang('the site is already there'), 'error');
            redirect($redirect);
        }

        Validation::Limits($link_title, lang('title'), '24', '250', $redirect);
        Validation::Limits($link_content, lang('description'), '24', '1500', $redirect);

        $data = [
            'link_url'          => $link_url,
            'link_url_domain'   => $link_url_domain,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_user_id'      => $this->uid['user_id'],
            'link_type'         => 0,
            'link_status'       => 200,
            'link_category_id'  => 1,
        ];

        WebModel::add($data);

        redirect($redirect);
    }

    // Форма редактирование домена
    public function editPage()
    {
        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getLinkId($domain_id);

        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        $meta = [
            'meta_title'    => lang('change the site') . ' | ' . $domain['link_url_domain'],
            'sheet'         => 'domains',
        ];

        $data = [
            'domain'        => $domain,
            'sheet'         => 'domains',
            'topic_select'  => WebModel::getLinkTopic($domain['link_id']),
        ];

        return view('/admin/web/edit', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Изменение домена
    public function edit()
    {
        $redirect   = '/web';
        $link_id    = Request::getPostInt('link_id');
        if (!$link  = WebModel::getLinkId($link_id)) {
            redirect($redirect);
        }

        $link_url       = Request::getPost('link_url');
        $link_title     = Request::getPost('link_title');
        $link_content   = Request::getPost('link_content');
        $url_domain     = Request::getPost('link_domain');
        $link_status    = Request::getPostInt('link_status');

        $post_fields    = Request::getPost() ?? [];
        $topics         = $post_fields['topic_select'] ?? [];

        Validation::Limits($link_title, lang('title'), '24', '250', $redirect);
        Validation::Limits($link_content, lang('description'), '24', '1500', $redirect);

        $data = [
            'link_id'           => $link['link_id'],
            'link_url'          => $link_url,
            'link_url_domain'   => $url_domain,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_user_id'      => $this->uid['user_id'],
            'link_type'         => 0,
            'link_status'       => $link_status,
            'link_category_id'  => 1,
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

    public function favicon()
    {
        $link_id    = Request::getPostInt('id');
        $link       = WebModel::getLinkId($link_id);
        Base::PageError404($link);

        $puth = HLEB_PUBLIC_DIR . '/uploads/favicons/' . $link["link_id"] . '.png';
        $dirF = HLEB_PUBLIC_DIR . '/uploads/favicons/';

        if (!file_exists($puth)) {
            $urls = self::getFavicon($link['link_url_domain']);
            copy($urls, $puth);
        }

        return true;
    }

    public static function getFavicon($url)
    {
        $url = str_replace("https://", '', $url);
        return "https://www.google.com/s2/favicons?domain=" . $url;
    }
}
