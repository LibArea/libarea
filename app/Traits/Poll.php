<?php

namespace App\Traits;

use App\Models\PollModel;

trait Poll
{
    public function getPoll($poll_id)
    {
        $count      = PollModel::getAllVotesCount($poll_id);

        return  [
            'question'  => PollModel::getQuestion($poll_id),
            'answers'   => PollModel::getAnswers($poll_id),
            'count'     => $count['sum'],
            'isVote'    => PollModel::isVote($poll_id)
        ];
    }
}
