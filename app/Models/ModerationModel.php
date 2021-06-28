<?php

namespace App\Models;

use XdORM\XD;

class ModerationModel extends \MainModel
{
 
    public static function getModerations()
    {
        $q = XD::select('*')->from(['moderations']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['mod_moderates_user_id'])
                ->leftJoin(['posts'])->on(['post_id'], '=', ['mod_post_id'])
                ->orderBy(['mod_id'])->desc()->limit(25);

        $result = $query->getSelect();

        return $result; 
    }
    
    public static function moderationsAdd($data) 
    {
       XD::insertInto(['moderations'], '(', 
            ['mod_moderates_user_id'], ',', 
            ['mod_moderates_user_tl'], ',', 
            ['mod_created_at'], ',',
            ['mod_post_id'], ',',  
            ['mod_content_id'], ',',
            ['mod_action'], ',', 
            ['mod_reason'], ')')->values( '(', 

        XD::setList([
            $data['user_id'], 
            $data['user_tl'], 
            $data['created_at'], 
            $data['post_id'], 
            $data['content_id'], 
            $data['action'], 
            $data['reason']]), ')' )->run();

        return true; 
    }
}