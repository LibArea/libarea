<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{FacetModel, SubscriptionModel};
use Validation, Meta, UserData;

class AddFacetController extends Controller
{
    // Add form: topic | blog | category
    public function index($facet_type)
    {
        return $this->render(
            '/facets/add',
            'base',
            [
                'meta'  => Meta::get(__('app.add_' . $facet_type)),
                'data'  => [
                    'type' => $facet_type,
                ]
            ]
        );
    }

    // Add topic | blog | category
    public function create($facet_type)
    {
        $facet_title                = Request::getPost('facet_title');
        $facet_description          = Request::getPost('facet_description');
        $facet_short_description    = Request::getPost('facet_short_description');
        $facet_slug                 = Request::getPost('facet_slug');
        $facet_seo_title            = Request::getPost('facet_seo_title');

        $redirect = ($facet_type == 'category') ? url('web') : url($facet_type . '.add');
        if ($facet_type == 'blog') {
            if (!UserData::checkAdmin()) {
                if (in_array($facet_slug, config('stop-blog'))) {
                    return json_encode(['error' => 'error', 'text' => __('msg.url_reserved')]);
                    //Validation::ComeBack('msg.url_reserved', 'error', $redirect);
                }
            }
        }

        if (!Validation::length($facet_title, 3, 64)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.title') . '»'])]);
        }

        if (!Validation::length($facet_description, 34, 225)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.meta_description') . '»'])]);
        }

        if (!Validation::length($facet_short_description, 9, 160)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.short_description') . '»'])]);
        }

        if (!Validation::length($facet_seo_title, 4, 225)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.slug') . '»'])]);
        }


        // Slug
        if (!Validation::length($facet_slug, 3, 43)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.slug') . '»'])]);
        }

        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $facet_slug)) {
            return json_encode(['error' => 'error', 'text' => __('msg.slug_correctness', ['name' => '«' . __('msg.slug') . '»'])]);
        }

        if (FacetModel::uniqueSlug($facet_slug, $facet_type)) {
            return json_encode(['error' => 'error', 'text' => __('msg.repeat_url')]);
        }

        if (preg_match('/\s/', $facet_slug) || strpos($facet_slug, ' ')) {
            return json_encode(['error' => 'error', 'text' => __('msg.url_gaps')]);
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

        return true;
    }
}
