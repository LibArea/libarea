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

        if ($domain = self::getDomain($data['url'])) {
            is_return(__('web.site_replay'), 'error', $redirect);
        }

        self::length($data['title'], 14, 250, 'title', $redirect);

        $basic_host =  self::domain($data['url']);

        return $basic_host;
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

    public static function getDomain($url)
    {
        $basic_host = self::domain($url);

        return WebModel::getItemOne($basic_host, 1);
    }

    public static function domain($url)
    {
        $parse  = parse_url($url);
        $domain = new Domain($parse['host']);

        return $domain->getRegisterable();
    }
}
