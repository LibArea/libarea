<?php

declare(strict_types=1);

namespace App\Services\Сheck;

use App\Models\AnswerModel;

class AnswerPresence
{
    public function index(int $answer_id) : array
    {
        $answer = AnswerModel::getAnswerId($answer_id);
        
        notEmptyOrView404($answer);

        return $answer;
    }
}
