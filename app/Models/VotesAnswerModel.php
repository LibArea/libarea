<?php

namespace App\Models;
use XdORM\XD;

class VotesAnswerModel extends \MainModel
{

    // Информация по комментарию по его id
    public static function infoAnsw($answ_id) {

         $q = XD::select('*')->from(['answers']);
         $query = $q->where(['answer_id'], '=', $answ_id);
         $info = $q->getSelectOne();
 
 		 return $info;
    }

    // Проверяем, голосовал ли пользователь за комментарий
    public static function getVoteStatus($answ_id, $uid)
	{

        $q = XD::select('*')->from(['votes_answ']);
        $query = $q->where(['votes_answ_item_id'], '=', $answ_id)->and(['votes_answ_user_id'], '=', $uid); 
          
        $info = $query->getSelect();
 
        if($info) {
            return $result = 1;
        } else {
            return false;
        }

	}
    
    // Записываем лайк за комментарий
    public static function saveVoteAnsw($answ_id, $up, $ip, $user_id, $date)
    {
        
        // var_dump() и для строки ->toString() используем 
        XD::insertInto(['votes_answ'], '(', ['votes_answ_item_id'], ',', ['votes_answ_points'], ',', ['votes_answ_ip'], ',', ['votes_answ_user_id'], ',', ['votes_answ_date'], ')')->values( '(', XD::setList([$answ_id, $up, $ip, $user_id, $date]), ')' )->run();

        return true;  
        
    }
 
}