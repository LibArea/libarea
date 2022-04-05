<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{FacetModel, SubscriptionModel};
use Validation, Config, Translate, Tpl, Meta, Html, UserData;

class AddFacetController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Add form topic | blog | category
    public function index($type)
    {
        self::limitFacer($type, 'redirect');

        return Tpl::agRender(
            '/facets/add',
            [
                'meta'  => Meta::get([], sprintf(Translate::get('add.option'), Translate::get('topics'))),
                'data'  => [
                    'type' => $type,
                ]
            ]
        );
    }

    // Add topic | blog | category
    public function create($facet_type)
    {
        $this->limitFacer($facet_type, 'redirect');

        $facet_title                = Request::getPost('facet_title');
        $facet_description          = Request::getPost('facet_description');
        $facet_short_description    = Request::getPost('facet_short_description');
        $facet_slug                 = Request::getPost('facet_slug');
        $facet_seo_title            = Request::getPost('facet_seo_title');

        $redirect = ($facet_type == 'category') ? getUrlByName('web') : getUrlByName($facet_type . '.add');
        if ($facet_type == 'blog') {
            if ($this->user['trust_level'] != UserData::REGISTERED_ADMIN) {
                if (in_array($facet_slug, Config::get('stop.blog'))) {
                    Html::addMsg('url.reserved', 'error');
                    redirect($redirect);
                }
            }
        }

        Validation::Slug($facet_slug, 'Slug (url)', $redirect);
        Validation::Length($facet_title, Translate::get('title'), '3', '64', $redirect);
        Validation::Length($facet_description, Translate::get('meta.description'), '34', '225', $redirect);
        Validation::Length($facet_short_description, Translate::get('short.description'), '9', '160', $redirect);
        Validation::Length($facet_slug, Translate::get('slug'), '3', '43', $redirect);
        Validation::Length($facet_seo_title, Translate::get('slug'), '4', '225', $redirect);

        if (FacetModel::uniqueSlug($facet_slug, $facet_type)) {
            Html::addMsg('repeat.url', 'error');
            redirect($redirect);
        }

        if (preg_match('/\s/', $facet_slug) || strpos($facet_slug, ' ')) {
            Html::addMsg('url.gaps', 'error');
            redirect($redirect);
        }

        $type = $facet_type ?? 'topic';
        $facet_slug = strtolower($facet_slug);

        $new_facet_id = FacetModel::add(
            [
                'facet_title'                => $facet_title,
                'facet_description'         => $facet_description,
                'facet_short_description'   => $facet_short_description,
                'facet_slug'                => $facet_slug,
                'facet_img'                 => 'facet-default.png',
                'facet_seo_title'           => $facet_seo_title,
                'facet_user_id'             => $this->user['id'],
                'facet_type'                => $type,
            ]
        );

        SubscriptionModel::focus($new_facet_id['facet_id'], $this->user['id'], 'facet');

        $redirect = $facet_type == 'category' ? getUrlByName('web') : '/' . $facet_type . '/' . $facet_slug;
        redirect($redirect);
    }

    public function limitFacer($type, $action)
    {
        $count      = FacetModel::countFacetsUser($this->user['id'], $type);

        $count_add  = UserData::checkAdmin() ? 999 : Config::get('trust-levels.count_add_' . $type);

        $in_total   = $count_add - $count;

        if ($action == 'no.redirect') {
            return $in_total;
        }

        self::tl($this->user['trust_level'], Config::get('trust-levels.tl_add_' . $type), $count, $count_add);

        if (!$in_total > 0) {
            redirect('/');
        }

        return $in_total;
    }

    public static function tl($trust_level, $allowed_tl, $count_content, $count_total)
    {
        if ($trust_level < $allowed_tl) {
            redirect('/');
        }

        if ($count_content >= $count_total) {
            redirect('/');
        }

        return true;
    }
}
