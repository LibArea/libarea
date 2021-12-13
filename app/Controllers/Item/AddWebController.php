<?php

namespace App\Controllers\Item;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FacetModel};
use Base, Validation, Translate, Domains;

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
        Validation::validTl($this->uid['user_trust_level'], Base::USER_LEVEL_ADMIN, 0, 1);

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return view(
            '/item/add',
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
        Validation::validTl($this->uid['user_trust_level'], Base::USER_LEVEL_ADMIN, 0, 1);

        $item_url           = Request::getPost('url');
        $item_title_url     = Request::getPost('title_url');
        $item_content_url   = Request::getPost('content_url');

        $redirect = getUrlByName('web.add');
        Validation::checkUrl($item_url, 'URL', $redirect);

        $parse              = parse_url($item_url);
        $url_domain         = $parse['host'];
        $domain             = new Domains($url_domain);
        $item_url_domain    = $domain->getRegisterable();
        $item_url           = $parse['scheme'] . '://' . $parse['host'];

        $item = WebModel::getItemOne($item_url_domain, $this->uid['user_id']);
        if ($item) {
            addMsg(Translate::get('the site is already there'), 'error');
            redirect($redirect);
        }

        Validation::Limits($item_title_url, Translate::get('title'), '14', '250', $redirect);
        Validation::Limits($item_content_url, Translate::get('description'), '24', '1500', $redirect);

        $data = [
            'item_url'          => $item_url,
            'item_url_domain'   => $item_url_domain,
            'item_title_url'    => $item_title_url,
            'item_content_url'  => $item_content_url,
            'item_published'    => 1,
            'item_user_id'      => $this->uid['user_id'],
            'item_type_url'     => 0,
            'item_status_url'   => 200,
        ];

        $item_topic = WebModel::add($data);

        // Фасеты для сайте
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $ket => $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item_topic['item_id']);
        }

        redirect(getUrlByName('web'));
    }
}
