<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\СonsoleModel;
use Agouti\SendEmail;

class СonsoleController extends MainController
{
    public static function updateCountPostTopic()
    {
        СonsoleModel::recalculateTopic();

        self::consoleRedirect();
    }
    
    public static function updateCountUp()
    {
        $users_id = СonsoleModel::allUsers();
        foreach ($users_id as $ind => $row) {
            $row['count']   =  СonsoleModel::allUp($row['user_id']);
            СonsoleModel::setAllUp($row['user_id'], $row['count']);
        }
        
        self::consoleRedirect();
    }
    
    public static function testMail()
    {
        $email  = Request::getPost('mail');
        SendEmail::send($email, 'Testing mail', 'The body of the message...');
        
        self::consoleRedirect();
    }
    
    public static function consoleRedirect()
    {
        if (PHP_SAPI != 'cli') {
            addMsg(lang('the command is executed'), 'success');
            redirect(getUrlByName('admin.tools'));
        }
        return true;
    }
}
