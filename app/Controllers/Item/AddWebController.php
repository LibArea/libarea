<?php

namespace App\Controllers\Item;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{WebModel, FacetModel};
use Validation, Translate, Domains, Tpl;

class AddWebController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма добавление домена
    public function index($sheet, $type)
    {
        Validation::validTl($this->user['trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return Tpl::agRender(
            '/item/add',
            [
                'meta'  => meta($m = [], Translate::get('add a website')),
                'data'  => [
                    'sheet' => $sheet,
                    'type'  => $type,
                ]
            ]
        );
    }

    public function create()
    {
        Validation::validTl($this->user['trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        $item_url           = Request::getPost('url');
        $item_title_url     = Request::getPost('title_url');
        $item_content_url   = Request::getPost('content_url');

        $redirect = getUrlByName('site.add');
        Validation::Url($item_url, 'URL', $redirect);

        $parse              = parse_url($item_url);
        $url_domain         = $parse['host'];
        $domain             = new Domains($url_domain);
        $item_url_domain    = $domain->getRegisterable();
        $item_url           = $parse['scheme'] . '://' . $parse['host'];

        $item = WebModel::getItemOne($item_url_domain, $this->user['id']);
        if ($item) {
            addMsg(Translate::get('the site is already there'), 'error');
            redirect($redirect);
        }

        Validation::Length($item_title_url, Translate::get('title'), '14', '250', $redirect);
        Validation::Length($item_content_url, Translate::get('description'), '24', '1500', $redirect);

        $item_topic = WebModel::add(
            [
                'item_url'          => $item_url,
                'item_url_domain'   => $item_url_domain,
                'item_title_url'    => $item_title_url,
                'item_content_url'  => $item_content_url,
                'item_published'    => 1,
                'item_user_id'      => $this->user['id'],
                'item_type_url'     => 0,
                'item_status_url'   => 200,
                'item_is_soft'      => 0,
                'item_is_github'    => 0,
                'item_votes'        => 0,
                'item_count'        => 1,
            ]
        );

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
