<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Modules\Admin\Models\SettingModel;
use Meta, Msg;

class SettingController extends Module
{
    private static ?array $cache = null;

    public function index($type): View
    {
        return view(
            '/setting/' . $type,
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data'  => [
                    'type'      => $type,
                    'settings'  => SettingModel::get(),
                ]
            ]
        );
    }

    public function edit()
    {
        if (!is_array($data = $_POST)) {
            return false;
        }

        foreach ($data as $key => $val) {
            SettingModel::change($key, $val);
        }

        Msg::redirect(__('msg.change_saved'), 'success', url('admin.settings.general'));
    }

    public static function get($key)
    {
        if (is_null(self::$cache)) {
            self::$cache = (array)SettingModel::get();
        }

        $settings = [];
        foreach (self::$cache as $val) {
            $settings[$val['val']] = $val['value'];
        }

        return $settings[$key] ?? 'no ' . $key;
    }
}
