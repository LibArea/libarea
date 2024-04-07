<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\DeviceIDModel;

class DeviceController extends Controller
{
    public static function index()
    {
        return DeviceIDModel::get();
    }

    public static function set(): bool
    {
        $id = Request::post('id')->asInt();

        DeviceIDModel::create($id);

        return true;
    }
}
