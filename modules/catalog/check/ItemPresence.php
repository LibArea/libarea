<?php

declare(strict_types=1);

namespace Modules\Catalog\Сheck;

use Modules\Catalog\Models\WebModel;

class ItemPresence
{
    public static function index(int $id): array
    {
        $item = WebModel::getItemId($id);

        notEmptyOrView404($item);

        return $item;
    }
}
