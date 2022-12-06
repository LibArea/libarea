<?php

declare(strict_types=1);

namespace App\Services\Сheck;

use App\Models\Item\WebModel;

class ItemPresence
{
    public function index(int $element) : array
    {
        $item = WebModel::getItemId($id);
        
        notEmptyOrView404($item);

        return $item;
    }
}
