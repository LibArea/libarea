<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\DeviceIDModel;

class DeviceID extends Base
{
    public static function get()
    {
        return DeviceIDModel::get();
    }
	
    public static function set()
    {
		$id = Request::getPostInt('id');
		
        return DeviceIDModel::create($id);
    }
}
