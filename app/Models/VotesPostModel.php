<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class VotesPostModel extends \MainModel
{
    // Информация по посту по его id
    public static function infoPost($post_id) 
    {
        if(!$post_id) { return false; }
        return XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
    }

    // Проверяем, голосовал ли пользователь за пост
    public static function getVoteStatus($post_id, $uid)
	{
        $q = XD::select('*')->from(['votes_post']);
        $query = $q->where(['votes_post_item_id'], '=', $post_id)->and(['votes_post_user_id'], '=', $uid); 
          
        $info = $query->getSelect();
 
        if($info) {
            return $result = 1;
        } else {
            return false;
        }

	}
    
    // Записываем лайк за пост
    public static function saveVote($post_id, $up, $ip, $user_id, $date)
    {
        XD::insertInto(['votes_post'], '(', ['votes_post_item_id'], ',', ['votes_post_points'], ',', ['votes_post_ip'], ',', ['votes_post_user_id'], ',', ['votes_post_date'], ')')->values( '(', XD::setList([$post_id, $up, $ip, $user_id, $date]), ')' )->run();

        return true;  
    }
    
    // Записываем голосование за пост
    public static function saveVotePost($post_id)
    {
        $sql = "UPDATE posts SET post_votes = (post_votes + 1) WHERE post_id = :post_id";
        DB::run($sql,['post_id' => $post_id]);
        
        return true; 
    }
 
}