<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\SettingModel;
use Meta;

class Setting
{
    public function index($type)
    {   
        return view(
            '/view/default/setting/' . $type,
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data'  => [
                    'type'  => $type,
                    'settings' => SettingModel::get(),
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
}
