<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use Hleb\Static\Request;

class PollModel extends Model
{
    public static function getQuestion($question_id)
    {
        $sql = "SELECT poll_id, poll_title, poll_user_id, poll_date, poll_is_closed FROM polls WHERE poll_id = :question_id";

        return DB::run($sql, ['question_id' => $question_id])->fetch();
    }

    public static function getAllVotesCount($question_id)
    {
        $sql = "SELECT SUM(answer_votes) as sum FROM polls_answers WHERE answer_question_id = :question_id";

        return DB::run($sql, ['question_id' => $question_id])->fetch();
    }

    public static function getAnswers($question_id)
    {
        $sql = "SELECT answer_id, answer_title, answer_votes FROM polls_answers WHERE answer_question_id = :question_id";

        return DB::run($sql, ['question_id' => $question_id])->fetchAll();
    }

    public static function getAnswersCount($question_id)
    {
        $sql = "SELECT answer_id FROM polls_answers WHERE answer_question_id = :question_id";

        return DB::run($sql, ['question_id' => $question_id])->rowCount();
    }

    public static function getUserQuestionsPolls($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT poll_id, poll_title, poll_date FROM polls WHERE poll_user_id = :user_id ORDER BY poll_id DESC LIMIT :start, :limit";

        return DB::run($sql, ['user_id' => self::container()->user()->id(), 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUserQuestionsPollsCount()
    {
        $sql = "SELECT poll_title, poll_date FROM polls WHERE poll_user_id = :user_id";

        return DB::run($sql, ['user_id' => self::container()->user()->id()])->rowCount();
    }

    public static function createQuestion($title)
    {
        DB::run("INSERT INTO polls(poll_title, poll_user_id) VALUES(:title, :user_id)", ['title' => $title, 'user_id' => self::container()->user()->id()]);

        $sql =  DB::run("SELECT LAST_INSERT_ID() as id")->fetch();

        return $sql['id'];
    }

    public static function createAnswers($key, $val, $id)
    {
        if (is_int($key)) {
            DB::run("INSERT INTO polls_answers(answer_question_id, answer_title) VALUES(:question_id, :title)", ['question_id' => $id, 'title' => $val]);
        }

        return;
    }

    public static function isVote($question_id)
    {
        $sql = "SELECT vote_answer_id, vote_date FROM polls_votes WHERE vote_question_id = :question_id AND vote_user_id = :user_id";

        return DB::run($sql, ['question_id' => $question_id, 'user_id' => self::container()->user()->id()])->fetch();
    }

    public static function vote($question_id, $answer_id)
    {
        if (self::isVote($question_id)) {
            return true;
        }

        $sql = "UPDATE polls_answers SET answer_votes = answer_votes + 1 WHERE answer_id = :id AND answer_question_id = :question_id";

        DB::run($sql, ['question_id' => $question_id, 'id' => $answer_id]);

        $sql = "INSERT INTO polls_votes(vote_question_id, vote_answer_id, vote_user_id, vote_ip) VALUES(:question_id, :answer_id, :user_id, :ip)";

        return DB::run($sql, ['question_id' => $question_id, 'answer_id' => $answer_id, 'user_id' => self::container()->user()->id(), 'ip' => Request::getUri()->getIp()]);
    }

    public static function editTitle($question_id, $title)
    {
        $sql = "UPDATE polls SET poll_title = :title WHERE poll_id = :question_id";

        DB::run($sql, ['question_id' => $question_id, 'title' => $title]);
    }

    public static function editClosed($question_id, $is_closed)
    {
        $sql = "UPDATE polls SET poll_is_closed = :is_closed WHERE poll_id = :question_id";

        DB::run($sql, ['question_id' => $question_id, 'is_closed' => $is_closed]);
    }

    public static function editAnswers($key, $title, $question_id)
    {
        $sql = "UPDATE polls_answers SET answer_title = :title WHERE answer_question_id = :question_id AND answer_id = :id";

        DB::run($sql, ['id' => $key, 'title' => $title, 'question_id' => $question_id]);
    }

    public static function accordance($answer_id)
    {
        $sql = "SELECT poll_user_id 
                    FROM polls_answers 
                        LEFT JOIN polls ON poll_id = answer_question_id 
                            WHERE answer_id = :answer_id  AND poll_user_id = :user_id";

        return DB::run($sql, ['answer_id' => $answer_id, 'user_id' => self::container()->user()->id()])->fetch();
    }

    public static function delVariant($answer_id)
    {
        if (!self::accordance($answer_id)) {
            return true;
        }

        return DB::run("DELETE FROM polls_answers WHERE answer_id = :answer_id", ['answer_id' => $answer_id]);
    }
}
