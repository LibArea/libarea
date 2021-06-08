<?php

namespace App\Models;
use XdORM\XD;

class VotesCommentModel extends \MainModel
{

    // Информация по комментарию по его id
    public static function infoCommentId($comm_id) 
    {
         return XD::select('*')->from(['comments'])->where(['comment_id'], '=', $comm_id)->getSelectOne();
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
    public static function saveVoteUp($comm_id, $up, $ip, $user_id, $date)
    {
        XD::insertInto(['votes_comm'], '(', ['votes_comm_item_id'], ',', ['votes_comm_points'], ',', ['votes_comm_ip'], ',', ['votes_comm_user_id'], ',', ['votes_comm_date'], ')')->values( '(', XD::setList([$comm_id, $up, $ip, $user_id, $date]), ')' )->run();

        return true;  
    }
 
    // Записываем количество
    public static function saveVoteCommQuantity($votes, $comm_id)
    {
        XD::update(['comments'])->set(['comment_votes'], '=', $votes)->where(['comment_id'], '=', $comm_id)->run();
        return true;
    }
 
}