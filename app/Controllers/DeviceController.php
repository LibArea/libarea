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
        $id = trim((string)Request::post('id')->value());

        // Store the fingerprint as a string (do not truncate to int), max 64 chars
        // Храним отпечаток строкой (не усекаем в int), максимум 64 символа
        if ($id === '' || mb_strlen($id) > 64) {
            return false;
        }

        DeviceIDModel::create($id);

        return true;
    }
}
