<?php

declare(strict_types=1);

namespace App\Validate;

use App\Models\FacetModel;
use Hleb\Static\Container;
use Msg;

class RulesFacet extends Validator
{
    public static function rulesAdd($data, $facet_type)
    {
        $container = Container::getContainer();
        $redirect = ($facet_type === 'category') ? url('web') : url('facet.form.add', ['type' => $facet_type]);

        if ($facet_type === 'blog') {
            if (!$container->user()->admin()) {
                if (in_array($data['facet_slug'], config('stop-blog', 'list'))) {
                    Msg::redirect(__('msg.went_wrong'), 'error', $redirect);
                }
            }
        }

        self::Length($data['facet_title'], 3, 64, 'title', $redirect);
        self::Length($data['facet_short_description'], 9, 160, 'short_description', $redirect);

        // Slug
        self::Length($data['facet_slug'], 3, 43, 'slug', $redirect);

        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $data['facet_slug'])) {
            Msg::redirect(__('msg.slug_correctness', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        if (FacetModel::uniqueSlug($data['facet_slug'], $facet_type)) {
            Msg::redirect(__('msg.repeat_url'), 'error', $redirect);
        }

        if (preg_match('/\s/', $data['facet_slug']) || strpos($data['facet_slug'], ' ')) {
            Msg::redirect(__('msg.url_gaps'), 'error', $redirect);
        }
    }

    public static function rulesEdit($data, $facet)
    {
        $container = Container::getContainer();

        // ['topic', 'blog', 'category', 'section']
        if (!in_array($data['facet_type'], config('facets', 'permitted'))) {
            Msg::redirect(__('msg.went_wrong'), 'error');
        }

        if ($facet == false) {
            Msg::redirect(__('msg.went_wrong'), 'error');
        }

        $redirect = url('facet.form.edit', ['type' => $facet['facet_type'], 'id' => $facet['facet_id']]);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $container->user()->id() && !$container->user()->admin()) {
            Msg::redirect(__('msg.went_wrong'), 'error', $redirect);
        }

        // Изменять тип темы может только персонал
        $new_type = $facet['facet_type'];
        if ($data['facet_type'] != $facet['facet_type']) {
            if ($container->user()->admin()) $new_type = $data['facet_type'];
        }

        // Проверка длины
        self::Length($data['facet_title'], 3, 64, 'title', $redirect);
        self::Length($data['facet_description'], 3, 225, 'meta_description', $redirect);
        self::Length($data['facet_short_description'], 9, 160, 'short_description', $redirect);
        self::Length($data['facet_seo_title'], 3, 225, 'slug', $redirect);

        // Slug
        self::Length($data['facet_slug'], 3, 32, 'slug', $redirect);

        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $data['facet_slug'])) {
            Msg::redirect(__('msg.slug_correctness', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        if (preg_match('/\s/', $data['facet_slug']) || strpos($data['facet_slug'], ' ')) {
            Msg::redirect(__('msg.url_gaps'), 'error', $redirect);
        }

        // Проверим повтор URL                       
        if ($data['facet_slug'] != $facet['facet_slug']) {
            if (FacetModel::uniqueSlug($data['facet_slug'], $new_type)) {
                Msg::redirect(__('msg.repeat_url'), 'error', $redirect);
            }
        }

        return $new_type;
    }
}
