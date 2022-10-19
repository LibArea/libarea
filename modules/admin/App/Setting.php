<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\SettingModel;
use Meta;

class Setting
{
    private static $cache = null;

    public function index($type)
    {
        return view(
            '/view/default/setting/' . $type,
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data'  => [
                    'type'      => $type,
                    'settings'  => SettingModel::get(),
                ]
            ]
        );
    }

    public function change()
    {
        if (!is_array($data = $_POST)) {
            return false;
        }

        foreach ($data as $key => $val) {
            SettingModel::change($key, $val);
        }

        is_return(__('msg.change_saved'), 'success', url('admin.settings.general'));
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
