<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{FacetModel, SubscriptionModel, ActionModel};
use Meta, Msg;

use App\Validate\RulesFacet;

class AddFacetController extends Controller
{
    /**
     * Add form: topic | blog | category
     *
     * @return void
     */
    public function index()
    {
        $facet_type = Request::param('type')->asString();

        render(
            '/facets/add',
            [
                'meta'  => Meta::get(__('app.add_' . $facet_type)),
                'data'  => [
                    'type' => $facet_type,
                ]
            ]
        );
    }

    /**
     * Add topic | blog | category
     *
     * @return void
     */
    public function add()
    {
        $facet_type = Request::param('type')->asString();

        $data = Request::allPost();

        RulesFacet::rulesAdd($data, $facet_type);

        $type = $facet_type ?? 'topic';

        $new_facet_id = FacetModel::add(
            [
                'facet_title'               => $data['facet_title'],
                'facet_description'         => __('app.meta_description'),
                'facet_short_description'   => $data['facet_short_description'],
                'facet_slug'                => strtolower($data['facet_slug']),
                'facet_img'                 => 'facet-default.png',
                'facet_seo_title'           => $data['facet_short_description'],
                'facet_user_id'             => $this->container->user()->id(),
                'facet_type'                => $type,
            ]
        );

        SubscriptionModel::focus($new_facet_id['facet_id'], 'facet');

        $msg = $type === 'blog' ? __('msg.blog_added') : __('msg.change_saved');

        $url = url('redirect.facet', ['id' => $new_facet_id['facet_id']]);

        ActionModel::addLogs(
            [
                'id_content'    => $new_facet_id['facet_id'],
                'action_type'   => $type,
                'action_name'   => 'added',
                'url_content'   => $url,
            ]
        );

        Msg::redirect($msg, 'success', $url);
    }
}
