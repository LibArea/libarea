<?php

namespace App\Models;

use App\Models\UserModel;
use XdORM\XD;
use DB;
use PDO;

class NotificationsModel extends \MainModel
{
    // $action_type
    // 1 - сообщение
    // 2 - пост
    // 3 - ответ
    // 4 - комментарий
    // 5 - пост в чат
    // 6 - понравился пост
    // 7 - понравился ответ
    // 10 - обращение в постах (@login)
    // 11 - в ответах (@login)
    // 12 - в комментариях (@login)
    // 15 -  аудит

    // Лист уведомлений
    public static function listNotification($user_uid)
    {
        $query = XD::select('*')->from(['notifications'])
                ->leftJoin(['users'])->on(['id'], '=', ['sender_uid'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->orderBy(['notification_id'])->desc();

        return $query->getSelect();
    }  

    // Уведомление
    public static function usersNotification($user_uid)
    {
        $query = XD::select('*')->from(['notifications'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->and(['read_flag'], '=', 0);  

        return  $query->getSelectOne();
    }  


	// Уведомление
    // Пример: 2 - ответы
    // NotificationsModel::send($sender_uid, $recipient_uid, $type, $messages_dialog_id, $url, 1);
	public static function send($sender_uid, $recipient_uid, $action_type, $connection_type, $url, $model_type = 0)
	{
		if (!$recipient_uid)
		{
			return false;
		}

        // Настройки участника
		if (!$action_type OR !self::check_notification_setting($recipient_uid, $action_type))
		{
			// return false; 
		} 

        XD::insertInto(['notifications'], '(', ['sender_uid'], ',', ['recipient_uid'], ',', ['action_type'], ',', ['connection_type'], ',', ['url'], ',', ['read_flag'], ')')->values( '(', XD::setList([$sender_uid, $recipient_uid,$action_type, $connection_type, $url, 0]), ')' )->run();
       
		return true;
	}

	// Проверить настройки уведомлений указанного пользователя 
	public static function check_notification_setting($recipient_uid, $action_type)
	{
		if (!$action_type)
		{
			return false;
		}
         
		$notification_setting = UserModel::getNotificationSettingByUid($recipient_uid);

		if ($action_type)
		{
			return false;
		}

		return true;
	}
    
    // Кто подписан на данный вопрос / пост
    public static function getFocusUsersPost($post_id)
    {
        return XD::select(['signed_post_id', 'signed_user_id'])->from(['posts_signed'])
                ->where(['signed_post_id'], '=', $post_id)->getSelect();  
    } 
    
    // Список читаемых постов
    public static function getFocusPostUser($user_id)
    {
        return XD::select(['signed_post_id', 'signed_user_id'])->from(['posts_signed'])
                ->where(['signed_user_id'], '=', $user_id)->getSelect();    
    } 
    
    public static function getFocusPostsListUser($user_id)
    {
        $focus_posts = self::getFocusPostUser($user_id);
       
        $result = Array();
        foreach ($focus_posts as $ind => $row) {
            $result[$ind] = $row['signed_post_id'];
        } 
        
        $string = "WHERE post_id IN(".implode(',', $result).") AND post_draft = 0";
        
               $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    votes_post_item_id, votes_post_user_id,
                    id, login, avatar, 
                    space_id, space_slug, space_name, space_color
                    
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_post_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topics  
                                LEFT JOIN topics_post_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_post_id
                        ) AS rel
                            ON rel.relation_post_id = post_id 

            INNER JOIN users ON id = post_user_id
            INNER JOIN spaces ON space_id = post_space_id
            LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = :user_id
            $string  LIMIT 100"; 

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC); 
    }   
    
    // Оповещение просмотрено
    public static function updateMessagesUnread($uid, $notif_id)
    {
        XD::update(['notifications'])->set(['read_flag'], '=', 1)
                                 ->where(['recipient_uid'], '=', $uid)
                                 ->and(['notification_id'], '=', $notif_id)
                                 ->run();
        return true;
    } 
    
    public static function getNotification($id)
    {
        return  XD::select('*')->from(['notifications'])
                ->where(['notification_id'], '=', $id)->getSelectOne();
    }  

    public static function setRemove($user_id)
    {
        XD::update(['notifications'])->set(['read_flag'], '=', 1)
                                 ->where(['recipient_uid'], '=', $user_id)
                                 ->run();
        return true;
    }
}
