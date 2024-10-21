<?php

declare(strict_types=1);

namespace Modules\Catalog\Validate;

use App\Validate\Validator;
use Modules\Catalog\Models\WebModel;
use Utopia\Domains\Domain;
use Msg;

class RulesItem extends Validator
{
    public static function rulesAdd($data)
    {
        $redirect = url('item.form.add', endPart: false);

        self::url($data['url'], $redirect);

        if (WebModel::getHost(host($data['url']))) {
            Msg::redirect(__('web.site_replay'), 'error', $redirect);
        }

        self::length($data['title'], 14, 250, 'title', $redirect);

        return  self::getRegisterable($data['url']);
    }

    public static function rulesEdit($data)
    {
        $redirect = url('item.form.add', endPart: false);

        $item = WebModel::getItemId((int)$data['item_id']);
        if (!$item) {
            return true;
        }

        self::length($data['title'], 14, 250, 'title', $redirect);
        self::length($data['content'], 24, 1500, 'description', $redirect);

        self::url($data['url'], $redirect);

        return $item;
    }

    public static function getDomains($url)
    {
        return WebModel::getDomains($url);
    }

    /**
     * @throws \Exception
     */
    public static function getRegisterable($url): string
    {
        return (new Domain(host($url)))->getRegisterable();
    }
}
