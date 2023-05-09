<?php

namespace App\Validate;

use App\Models\Item\WebModel;
use Utopia\Domains\Domain;

class RulesItem extends Validator
{
    public static function rulesAdd($data)
    {
        $redirect = url('content.add', ['type' => 'item']);

        self::url($data['url'], $redirect);

        if (host($data['url'])) {
            is_return(__('web.site_replay'), 'error', $redirect);
        }

        self::length($data['title'], 14, 250, 'title', $redirect);

        return  self::getRegisterable($data['url']);
    }

    public static function rulesEdit($data)
    {
        $redirect = url('content.add', ['type' => 'item']);

        $item = WebModel::getItemId($data['item_id']);
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

    public static function getRegisterable($url)
    {
        $domain = new Domain(host($url));

        return $domain->getRegisterable();
    }
}
