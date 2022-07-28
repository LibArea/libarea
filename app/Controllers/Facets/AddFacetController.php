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

        $data = Request::getPost();

        $redirect = ($facet_type == 'category') ? url('web') : url($facet_type . '.add');
        if ($facet_type == 'blog') {
            if (!UserData::checkAdmin()) {
                if (in_array($facet_slug, config('stop-blog'))) {
                    is_return(__('msg.went_wrong'), 'error', $redirect);
                }
            }
        }

        Validation::Length($facet_title, 3, 64, 'title', $redirect);
        Validation::Length($facet_description, 34, 225, 'meta_description', $redirect);
        Validation::Length($facet_short_description, 9, 160, 'short_description', $redirect);
        Validation::Length($facet_seo_title, 4, 225, 'slug', $redirect);

        // Slug
        Validation::Length($facet_slug, 3, 43, 'slug', $redirect);

        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $facet_slug)) {
            is_return(__('msg.slug_correctness', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        if (FacetModel::uniqueSlug($facet_slug, $facet_type)) {
            is_return(__('msg.repeat_url'), 'error', $redirect);
        }

        if (preg_match('/\s/', $facet_slug) || strpos($facet_slug, ' ')) {
            is_return(__('msg.url_gaps'), 'error', $redirect);
        }

        $type = $facet_type ?? 'topic';
        $facet_slug = strtolower($facet_slug);

        $new_facet_id = FacetModel::add(
            [
                'facet_title'               => $facet_title,
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

        $msg = $type == 'blog' ? __('msg.blog_added') : __('msg.change_saved');
        is_return($msg, 'success', $redirect);
    }
}
