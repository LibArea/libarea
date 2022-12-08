<?php

declare(strict_types=1);

namespace App\Services\Сheck;

use App\Models\Item\WebModel;

class ItemPresence
{
    public static function index(int $id) : array
    {
        $item = WebModel::getItemId($id);
        
        notEmptyOrView404($item);

        return $item;
    }
}
