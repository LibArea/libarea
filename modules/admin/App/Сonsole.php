<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\СonsoleModel;
use SendEmail, Sass;

class Сonsole
{
    public static function topic()
    {
        СonsoleModel::recalculateTopic();

        self::consoleRedirect();
    }

    public static function up()
    {
        $users = СonsoleModel::allUsers();
        foreach ($users as $row) {
            $row['count']   =  СonsoleModel::allUp($row['id']);
            СonsoleModel::setAllUp($row['id'], $row['count']);
        }

        self::consoleRedirect();
    }

    // Если пользователь имеет нулевой уровень доверия (tl) но ему UP >=3, то повышаем до 1
    // If the user has a zero level of trust (tl) but he has UP >=3, then we raise it to 1
    public static function tl()
    {
        $users = СonsoleModel::getTrustLevel(0);
        foreach ($users as $ind => $row) {
            if ($row['up_count'] > 2) {
                СonsoleModel::setTrustLevel($row['id'], 1);
            }
        }

        self::consoleRedirect();
    }

    public static function testMail()
    {
        $email  = Request::getPost('mail');
        SendEmail::mailText(1, 'admin.test', ['email' => $email]);

        self::consoleRedirect();
    }

    public static function css()
    {
        Sass::collect();

        self::consoleRedirect();
    }

    public static function consoleRedirect()
    {
        if (PHP_SAPI != 'cli') {
            addMsg('the command is executed', 'success');
            redirect(getUrlByName('admin.tools'));
        }
        return true;
    }
}
