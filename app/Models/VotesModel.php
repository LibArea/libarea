<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class VotesModel extends \MainModel
{
    // Информация по контенту
    public static function authorId($content_id, $type) 
    {
        // $type = post / comment / answer / link
        // Таблица $type + 's' (например, posts)
        // Поля, пример: post_id
        $uid = XD::select('*')->from([$type.'s'])->where([$type.'_id'], '=', $content_id)->getSelectOne();
       
        return $uid[$type.'_user_id'];
    }

    // Проверяем, голосовал ли пользователь
    public static function voteStatus($content_id, $author_id, $type)
	{
        $q = XD::select('*')->from(['votes_'.$type])->where(['votes_'.$type.'_item_id'], '=', $content_id);
        $info = $q->and(['votes_'.$type.'_user_id'], '=', $author_id)->getSelect();
          
        if($info) {
            return false;
        } 
        
        return true;
	}
    
    // Записываем лайк
    public static function saveVote($content_id, $ip, $user_id, $date, $type)
    {
        XD::insertInto(['votes_'.$type], '(', ['votes_'.$type.'_item_id'], ',', ['votes_'.$type.'_points'], ',', ['votes_'.$type.'_ip'], ',', ['votes_'.$type.'_user_id'], ',', ['votes_'.$type.'_date'], ')')->values( '(', XD::setList([$content_id, 1, $ip, $user_id, $date]), ')' )->run();

        return true;  
    }
    
    // Записываем голосование в таблицу конктена
    public static function saveVoteContent($content_id, $type)
    {
        $sql = "UPDATE ".$type."s SET ".$type."_votes = (".$type."_votes + 1) WHERE ".$type."_id = :content_id";
        DB::run($sql,['content_id' => $content_id]);
        
        return true; 
    }
 
}