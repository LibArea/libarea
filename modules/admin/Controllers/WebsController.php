<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\WebModel;
use Lori\{Base, Validation};

class WebsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $domains    = WebModel::getLinksAll($page, $limit, $uid['user_id']);

        $data = [
            'meta_title'    => lang('Domains'),
            'sheet'         => $sheet == 'all' ? 'domains' : $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ];

        return view('/templates/web/webs', ['data' => $data, 'uid' => $uid, 'domains' => $domains]);
    }

    // Форма добавление домена
    public function addPage()
    {
        $uid    = Base::getUid();
        $data = [
            'meta_title'    => lang('Add a website'),
            'sheet'         => 'domains-add',
        ];

        return view('/templates/web/add', ['data' => $data, 'uid' => $uid]);
    }

    // Добавление домена
    public function add()
    {
        $uid    = Base::getUid();

        $link_url           = Request::getPost('link_url');
        $link_title         = Request::getPost('link_title');
        $link_content       = Request::getPost('link_content');

        $parse              = parse_url($link_url);
        $link_url_domain    = $parse['host'];
        $link_url           = $parse['scheme'] . '://' . $parse['host'];

        $redirect = '/web';
        $link = WebModel::getLinkOne($link_url_domain, $uid['user_id']);
        if ($link) {
            Base::addMsg(lang('The site is already there'), 'error');
            redirect($redirect);
        }

        Validation::Limits($link_title, lang('Title'), '24', '250', $redirect);
        Validation::Limits($link_content, lang('Description'), '24', '1500', $redirect);

        $data = [
            'link_url'          => $link_url,
            'link_url_domain'   => $link_url_domain,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_user_id'      => $uid['user_id'],
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
        $uid        = Base::getUid();
        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getLinkId($domain_id);

        $data = [
            'meta_title'    => lang('Change the site') . ' | ' . $domain['link_url_domain'],
            'sheet'         => 'admin',
        ];

        return view('/templates/web/edit', ['data' => $data, 'uid' => $uid, 'domain' => $domain]);
    }

    // Изменение домена
    public function edit()
    {
        $uid     = Base::getUid();
        $link_id = Request::getPostInt('link_id');

        $redirect = '/web';
        if (!$link = WebModel::getLinkId($link_id)) {
            redirect($redirect);
        }

        $link_url       = Request::getPost('link_url');
        $link_title     = Request::getPost('link_title');
        $link_content   = Request::getPost('link_content');
        $url_domain     = Request::getPost('link_domain');
        $link_status    = Request::getPostInt('link_status');

        Validation::Limits($link_title, lang('Title'), '24', '250', $redirect);
        Validation::Limits($link_content, lang('Description'), '24', '1500', $redirect);

        $data = [
            'link_id'           => $link['link_id'],
            'link_url'          => $link_url,
            'link_url_domain'   => $url_domain,
            'link_title'        => $link_title,
            'link_content'      => $link_content,
            'link_user_id'      => $uid['user_id'],
            'link_type'         => 0,
            'link_status'       => 200,
            'link_cat_id'       => 1,
        ];

        WebModel::editLink($data);

        redirect($redirect);
    }
}
