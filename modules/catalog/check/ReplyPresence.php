<?php

declare(strict_types=1);

namespace Modules\Catalog\Сheck;

use Modules\Catalog\Models\ReplyModel;

class ReplyPresence
{
    public static function index(int $reply_id): array
    {
        $reply = ReplyModel::getId($reply_id);

        notEmptyOrView404($reply);

        return $reply;
    }
}
