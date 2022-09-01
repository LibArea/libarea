<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{FacetModel, SubscriptionModel};
use Meta;

use App\Validate\RulesFacet;

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
        $data = Request::getPost();

        RulesFacet::rulesAdd($data, $facet_type);

        $type = $facet_type ?? 'topic';

        $new_facet_id = FacetModel::add(
            [
                'facet_title'               => $data['facet_title'],
                'facet_description'         => $data['facet_description'],
                'facet_short_description'   => $data['facet_short_description'],
                'facet_slug'                => strtolower($data['facet_slug']),
                'facet_img'                 => 'facet-default.png',
                'facet_seo_title'           => $data['facet_seo_title'],
                'facet_user_id'             => $this->user['id'],
                'facet_type'                => $type,
            ]
        );

        SubscriptionModel::focus($new_facet_id['facet_id'], $this->user['id'], 'facet');

        $msg = $type == 'blog' ? __('msg.blog_added') : __('msg.change_saved');

        is_return($msg, 'success', url('redirect.facet', ['id' => $new_facet_id['facet_id']]));
    }
}
