<?php

namespace App\Models;
use XdORM\XD;

class VotesAnswerModel extends \MainModel
{
    // Информация по ответу по его id
    public static function infoAnswerId($answ_id) 
    {
        return XD::select('*')->from(['answers'])->where(['answer_id'], '=', $answ_id)->getSelectOne();
    }

    // Проверяем, голосовал ли пользователь за ответ
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
    
    // Записываем лайк за ответы
    public static function saveVoteUp($answ_id, $up, $ip, $user_id, $date)
    {
        XD::insertInto(['votes_answ'], '(', ['votes_answ_item_id'], ',', ['votes_answ_points'], ',', ['votes_answ_ip'], ',', ['votes_answ_user_id'], ',', ['votes_answ_date'], ')')->values( '(', XD::setList([$answ_id, $up, $ip, $user_id, $date]), ')' )->run();

        return true;  
    }
 
    // Записываем количество
    public static function saveVoteAnswerQuantity($votes, $answ_id)
    {
        XD::update(['answers'])->set(['answer_votes'], '=', $votes)->where(['answer_id'], '=', $answ_id)->run();
        return true; 
    }

}