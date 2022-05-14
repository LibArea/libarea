<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{FacetModel, SubscriptionModel};
use Validation, Meta, UserData;

class AddFacetController extends Controller
{
    // Add form topic | blog | category
    public function index($type)
    {
        self::limitFacer($type, 'redirect');

        return $this->render(
            '/facets/add',
            [
                'meta'  => Meta::get(__('app.add_' . $type)),
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

        $redirect = ($facet_type == 'category') ? url('web') : url($facet_type . '.add');
        if ($facet_type == 'blog') {
            if (!UserData::checkAdmin()) {
                if (in_array($facet_slug, config('stop-blog'))) {
                    Validation::ComeBack('msg.url_reserved', 'error', $redirect);
                }
            }
        }

        Validation::Slug($facet_slug, 'msg.slug', $redirect);
        Validation::Length($facet_title, 'msg.title', '3', '64', $redirect);
        Validation::Length($facet_description, 'msg.meta_description', '34', '225', $redirect);
        Validation::Length($facet_short_description, 'msg.short_description', '9', '160', $redirect);
        Validation::Length($facet_slug, 'msg.slug', '3', '43', $redirect);
        Validation::Length($facet_seo_title, 'msg.slug', '4', '225', $redirect);

        if (FacetModel::uniqueSlug($facet_slug, $facet_type)) {
            Validation::ComeBack('msg.repeat_url', 'error', $redirect);
        }

        if (preg_match('/\s/', $facet_slug) || strpos($facet_slug, ' ')) {
            Validation::ComeBack('msg.url_gaps', 'error', $redirect);
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

        $redirect = $facet_type == 'category' ? url('web') : '/' . $facet_type . '/' . $facet_slug;
        redirect($redirect);
    }

    public function limitFacer($type, $action)
    {
        $count      = FacetModel::countFacetsUser($this->user['id'], $type);

        $count_add  = UserData::checkAdmin() ? 999 : config('trust-levels.count_add_' . $type);

        $in_total   = $count_add - $count;

        if ($action == 'no.redirect') {
            return $in_total;
        }

        self::tl($this->user['trust_level'], config('trust-levels.tl_add_' . $type), $count, $count_add);

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
