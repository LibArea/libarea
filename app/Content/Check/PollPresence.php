<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use App\Models\PollModel;

class PollPresence
{
    public static function index(int $id): array
    {
        $question = PollModel::getQuestion($id);

        notEmptyOrView404($question);

        return $question;
    }
}
