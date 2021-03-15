<?php

namespace App\Models;
use XdORM\XD;
use DB;

class VotesCommentModel extends \MainModel
{

    // Информация по комментарию по его id
    public static function infoComm($comm_id) {

         $q = XD::select('*')->from(['comments']);
         $query = $q->where(['comment_id'], '=', $comm_id);
         $info = $q->getSelectOne();
 
 		 return $info;
    }

    // Проверяем, голосовал ли пользователь за комментарий
    public static function getVoteStatus($comm_id, $uid)
	{
    
        $q = XD::select('*')->from(['votes_comm']);
        $query = $q->where(['votes_comm_item_id'], '=', $comm_id)->and(['votes_comm_user_id'], '=', $uid); 
          
        $info = $query->getSelect();
 
        if($info) {
            return $result = 1;
        } else {
            return false;
        }

	}
    
    // Записываем лайк за комментарий
    public static function saveVote($comm_id, $up, $ip, $user_id, $date)
    {
        
        // var_dump() и для строки ->toString() используем 
        XD::insertInto(['votes_comm'], '(', ['votes_comm_item_id'], ',', ['votes_comm_points'], ',', ['votes_comm_ip'], ',', ['votes_comm_user_id'], ',', ['votes_comm_date'], ')')->values( '(', XD::setList([$comm_id, $up, $ip, $user_id, $date]), ')' )->run();

        return true;  
        
    }
 
}