<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FacetModel};
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

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

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

        Validation::Limits($link_title, Translate::get('title'), '14', '250', $redirect);
        Validation::Limits($link_content, Translate::get('description'), '24', '1500', $redirect);

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

        // Фасеты для сайте
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $ket => $row) {
               $arr[] = $row;
            }
            FacetModel::addLinkFacets($arr, $link_topic['link_id']);
        }

        redirect($redirect);
    }
}
