<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use Base, Validation;

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

        $meta = meta($m = [], lang('add a website'));
        $data = [
            'sheet'         => 'domains',
        ];

        return view('/web/add', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
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
        ];

        WebModel::add($data);

        redirect($redirect);
    }
}
