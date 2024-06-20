<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\PollModel;

trait Poll
{
    public function getPoll(int $poll_id): array
    {
        $count      = PollModel::getAllVotesCount($poll_id);

        return  [
            'question'  => PollModel::getQuestion($poll_id),
            'answers'   => PollModel::getAnswers($poll_id),
            'count'     => $count['sum'],
            'isVote'    => PollModel::isVote($poll_id)
        ];
    }
    
    private function selectPoll(null|string $params): int
    {
        if (!$params) {
            return 0;
        }

        $id = json_decode($params, true);

        return (int)$id[0]['id'];
    }
}
