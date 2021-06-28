<?php

namespace App\Models;
use XdORM\XD;

class FlowModel extends \MainModel
{
    
    // Вывод событий в поток
    public static function getFlowAll()
    {
        $q = XD::select('*')->from(['flow_log']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['flow_user_id'])
                ->where(['flow_action_type'], '!=', 'add_post')
                ->and(['flow_action_type'], '!=', 'vote_post')
                ->and(['flow_action_type'], '!=', 'vote_answer')
                ->and(['flow_action_type'], '!=', 'vote_comment')
                ->and(['flow_action_type'], '!=', 'vote_link')
                ->and(['flow_tl'], '=', 0)                 
                ->orderBy(['flow_id'])->desc()->limit(15);

        $result = $query->getSelect();

        return $result;
    }
    
    // Добавим в поток 
    public static function FlowAdd($data) 
    {
       XD::insertInto(['flow_log'], '(', 
            ['flow_action_type'], ',', 
            ['flow_content'], ',', 
            ['flow_user_id'], ',',
            ['flow_pubdate'], ',', 
            ['flow_url'], ',',  
            ['flow_target_id'], ',',
            ['flow_space_id'], ',', 
            ['flow_tl'], ',',
            ['flow_ip'], ')')->values( '(', 
        
        XD::setList([
            $data['flow_action_type'], 
            $data['flow_content'], 
            $data['flow_user_id'], 
            $data['flow_pubdate'], 
            $data['flow_url'], 
            $data['flow_target_id'], 
            $data['flow_space_id'], 
            $data['flow_tl'], 
            $data['flow_ip']]), ')' )->run();

        return true; 
    }
    
    // Удаляем / восстанавливаем 
    public static function FlowDelete($flow_id, $user_id) 
    {
        if (self::isTheFlowDeleted($flow_id) == 1) {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', 0)->where(['flow_id'], '=', $flow_id)->run();
        } else {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', $user_id)->where(['flow_id'], '=', $flow_id)->run();
        }
        
        return true;
    }
    
    // Удалена нить потока или нет
    public static function isTheFlowDeleted($flow_id) 
    {
        $result = XD::select('*')->from(['flow_log'])->where(['flow_id'], '=', $flow_id)->getSelectOne();
        return $result['flow_is_delete'];
    }
    
}