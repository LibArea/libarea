<?php

namespace App\Models;
use XdORM\XD;

class FlowModel extends \MainModel
{
    public static function getFlowAll()
    {
        $q = XD::select('*')->from(['flow_log']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['flow_user_id'])
                ->where(['flow_action_id'], '!=', 3) // 3 - up post
                ->and(['flow_action_id'], '!=', 4)   // 4 - up comment
                ->orderBy(['flow_id'])->desc()->limit(15);

        $result = $query->getSelect();

        return $result;
    }
    
    // Добавляем в поток 
    public static function FlowAdd($data) 
    {
       XD::insertInto(['flow_log'], '(', 
            ['flow_action_id'], ',', 
            ['flow_content'], ',', 
            ['flow_user_id'], ',',
            ['flow_pubdate'], ',', 
            ['flow_url'], ',',  
            ['flow_target_id'], ',',
            ['flow_about'], ',',
            ['flow_space_id'], ',', 
            ['flow_tl'], ',',
            ['flow_ip'], ')')->values( '(', 
        
        XD::setList([
            $data['flow_action_id'], 
            $data['flow_content'], 
            $data['flow_user_id'], 
            $data['flow_pubdate'], 
            $data['flow_url'], 
            $data['flow_target_id'], 
            $data['flow_about'], 
            $data['flow_space_id'], 
            $data['flow_tl'], 
            $data['flow_ip']]), ')' )->run();

        return true; 
    }
    
    // Удаляем / восстанавливаем 
    public static function FlowDelete($flow_id) 
    {
        if(self::isTheFlowDeleted($flow_id) == 1) {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', 0)->where(['flow_id'], '=', $flow_id)->run();
        } else {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', 1)->where(['flow_id'], '=', $flow_id)->run();
        }
        
        return true;
    }
    
    // Удален поток или нет
    public static function isTheFlowDeleted($flow_id) 
    {
        $result = XD::select('*')->from(['flow_log'])->where(['flow_id'], '=', $flow_id)->getSelectOne();
        return $result['flow_is_delete'];
    }
    
}