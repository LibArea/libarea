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
    
    private function selectPoll($poll_id)
    {
        if (!$poll_id) {
            return 0;
        }

        $id = json_decode($poll_id, true);

        return $id[0]['id'];
    }
}
