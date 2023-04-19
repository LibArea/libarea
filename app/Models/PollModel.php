<?php

/* 
CREATE TABLE `polls` (
  `poll_id` int(11) NOT NULL auto_increment,
  `poll_title` varchar(255) NOT NULL,
  `poll_user_id` int(11) NOT NULL,
  `poll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `poll_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `poll_tl` tinyint(1) NOT NULL DEFAULT 0,
  `poll_tl_is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `polls_answers` (
  `answer_id` int(11) NOT NULL auto_increment,
  `answer_question_id` int(11) default NULL,
  `answer_title` varchar(255) NOT NULL,
  `answer_votes` int(11) default 0,
  PRIMARY KEY  (`answer_id`),
  KEY `answer_question_id` (`answer_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `polls_votes` (
  `vote_id` int(11) NOT NULL auto_increment,
  `vote_question_id` int(11) NOT NULL,
  `vote_user_id` int(11) NOT NULL,
  `vote_ip` varchar(15) NOT NULL,
  `vote_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY  (`vote_id`),
  KEY `vote_question_id` (`vote_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

  ALTER TABLE `posts` ADD `post_poll` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT 'Для опросов' AFTER `post_closed`; 
  ALTER TABLE `items` ADD `item_poll` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT 'Для опроса' AFTER `item_following_link`;
*/

namespace App\Models;

use Hleb\Constructor\Handlers\Request;
use UserData;
use DB;


class PollModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getQuestion($question_id)
    {
        $sql = "SELECT poll_id, poll_title, poll_user_id, poll_date FROM polls WHERE poll_id = :question_id";

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

        return DB::run($sql, ['user_id' => UserData::getUserId(), 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUserQuestionsPollsCount()
    {
        $sql = "SELECT poll_title, poll_date FROM polls WHERE poll_user_id = :user_id";

        return DB::run($sql, ['user_id' => UserData::getUserId()])->rowCount();
    }

    public static function createQuestion($title)
    {
        DB::run("INSERT INTO polls(poll_title, poll_user_id) VALUES(:title, :user_id)", ['title' => $title, 'user_id' => UserData::getUserId()]);

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
        $sql = "SELECT vote_id FROM polls_votes WHERE vote_question_id = :question_id AND vote_user_id = :user_id";

        return DB::run($sql, ['question_id' => $question_id, 'user_id' => UserData::getUserId()])->fetch();
    }

    public static function vote($question_id, $answer_id)
    {
        if (self::isVote($question_id)) {
            return true;
        }

        $sql = "UPDATE polls_answers SET answer_votes = answer_votes + 1 WHERE answer_id = :id AND answer_question_id = :question_id";

        DB::run($sql, ['question_id' => $question_id, 'id' => $answer_id]);

        $sql = "INSERT INTO polls_votes(vote_question_id, vote_user_id, vote_ip) VALUES(:question_id, :user_id, :ip)";

        return DB::run($sql, ['question_id' => $question_id, 'user_id' => UserData::getUserId(), 'ip' => Request::getRemoteAddress()]);
    }

    public static function editTitle($question_id, $title)
    {
        $sql = "UPDATE polls SET poll_title = :title WHERE poll_id = :question_id";

        DB::run($sql, ['question_id' => $question_id, 'title' => $title]);
    }

    public static function editAnswers($key, $title, $question_id)
    {
        $sql = "UPDATE polls_answers SET answer_title = :title WHERE answer_question_id = :question_id AND answer_id = :id";

        DB::run($sql, ['id' => $key, 'title' => $title, 'question_id' => $question_id]);
    }
}
