<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{FacetModel, SubscriptionModel};
use Validation, Config, Translate, Tpl;

class AddFacetController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Add form topic
    public function index($sheet)
    {
        $limit   = self::limitFacer($sheet, 'redirect');

        return Tpl::agRender(
            '/facets/add',
            [
                'meta'  => meta($m = [], sprintf(Translate::get('add.option'), Translate::get('topics'))),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $sheet,
                    'count_facet'   => $limit,
                ]
            ]
        );
    }

    // Add topic / blog
    public function create($type)
    {
        self::limitFacer($type, 'redirect');

        $facet_title                = Request::getPost('facet_title');
        $facet_description          = Request::getPost('facet_description');
        $facet_short_description    = Request::getPost('facet_short_description');
        $facet_slug                 = Request::getPost('facet_slug');
        $facet_seo_title            = Request::getPost('facet_seo_title');

        $redirect = getUrlByName('topic.add');
        if ($type == 'blog') {
            $redirect = getUrlByName('blogs.my', ['login' => $this->user['login']]);
            if ($this->user['trust_level'] != UserData::REGISTERED_ADMIN) {
                if (in_array($facet_slug, Config::get('stop-blog'))) {
                    addMsg(Translate::get('stop-blog'), 'error');
                    redirect($redirect);
                }
            }
        }

        Validation::Slug($facet_slug, 'Slug (url)', $redirect);
        Validation::Length($facet_title, Translate::get('title'), '3', '64', $redirect);
        Validation::Length($facet_description, Translate::get('meta description'), '34', '225', $redirect);
        Validation::Length($facet_short_description, Translate::get('short description'), '9', '160', $redirect);
        Validation::Length($facet_slug, Translate::get('slug'), '3', '43', $redirect);
        Validation::Length($facet_seo_title, Translate::get('slug'), '4', '225', $redirect);

        if (FacetModel::getFacet($facet_slug, 'slug')) {
            addMsg(Translate::get('url-already-exists'), 'error');
            redirect($redirect);
        }

        if (preg_match('/\s/', $facet_slug) || strpos($facet_slug, ' ')) {
            addMsg(Translate::get('url-gaps'), 'error');
            redirect($redirect);
        }

        $type = $type ?? 'topic';
        $facet_slug = strtolower($facet_slug);
        $data = [
            'facet_title'               => $facet_title,
            'facet_description'         => $facet_description,
            'facet_short_description'   => $facet_short_description,
            'facet_slug'                => $facet_slug,
            'facet_img'                 => 'facet-default.png',
            'facet_add_date'            => date("Y-m-d H:i:s"),
            'facet_seo_title'           => $facet_seo_title,
            'facet_user_id'             => $this->user['id'],
            'facet_type'                => $type,
        ];

        $new_facet_id = FacetModel::add($data);

        SubscriptionModel::focus($new_facet_id['facet_id'], $this->user['id'], 'topic');

        redirect(getUrlByName($type, ['slug' => $facet_slug]));
    }

    public function limitFacer($type, $action)
    {
        $count      = FacetModel::countFacetsUser($this->user['id'], $type);

        $count_add  = UserData::checkAdmin() ? 999 : Config::get('trust-levels.count_add_' . $type);

        $in_total   = $count_add - $count;

        if ($action == 'no.redirect') {
            return $in_total;
        }

        Validation::validTl($this->user['trust_level'], Config::get('trust-levels.tl_add_' . $type), $count, $count_add);

        if (!$in_total > 0) {
            redirect('/');
        }

        return $in_total;
    }
}
