<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\SettingModel;
use Meta;

class Setting
{
    private static $cache = [];
    
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
        if (!is_array($data = Request::getPost()))
		{
			return false;
		}
        
		foreach ($data as $key => $val)
		{
			SettingModel::change($key, $val);
		}

		is_return(__('msg.change_saved'), 'success', url('admin.settings.general'));
    }
    
    public function get($key)
    {
        if (self::$cache) {
       
            foreach (self::$cache as $val)
            {
              $settings[$val['val']] = $val['value'];
            }

            return $settings[$key];
        }

        self::$cache = SettingModel::get();
    }
}
