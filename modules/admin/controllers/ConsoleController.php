<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use App\Bootstrap\Services\Auth\RegType;
use Modules\Admin\Models\ConsoleModel;
use SendEmail, Msg;

class ConsoleController extends Module
{
    public static function index()
    {
        $choice  = Request::post('type')->value();
        $allowed = ['css', 'topic', 'post', 'up', 'tl'];
        if (!in_array($choice, $allowed, true)) {
            redirect(url('admin.tools'));
        }
        self::$choice();
    }

    public static function topic()
    {
        ConsoleModel::recalculateTopic();

        self::consoleRedirect();
    }

    public static function post()
    {
        ConsoleModel::recalculateCountCommentPost();

        self::consoleRedirect();
    }

    public static function up()
    {
        $users = ConsoleModel::allUsers();
        foreach ($users as $row) {
            $row['count']   =  ConsoleModel::allUp($row['id']);
            ConsoleModel::setAllUp($row['id'], $row['count']);
        }

        self::consoleRedirect();
    }

    /**
     * If the user has a 1 level of trust (tl) but he has UP > 2, then we raise it to 2
     * Если пользователь имеет 1 уровень доверия (tl) но ему UP > 2, то повышаем до 2
     *
     * @return void
     */
    public static function tl()
    {
        $users = ConsoleModel::getTrustLevel(RegType::USER_FIRST_LEVEL);
        foreach ($users as $row) {
            if ($row['up_count'] > 2) {
                ConsoleModel::setTrustLevel($row['id'], RegType::USER_SECOND_LEVEL);
            }
        }

        self::consoleRedirect();
    }

    public static function testMail()
    {
        $email  = Request::post('mail')->value();
        SendEmail::mailText(1, 'admin.test', ['email' => $email]);

        Msg::add(__('admin.completed'), 'success');

        redirect(url('admin.tools'));
    }

    public static function css()
    {
        (new \Modules\Admin\Controllers\SassController)->collect();

        self::consoleRedirect();
    }

    public static function consoleRedirect()
    {
        if (PHP_SAPI !== 'cli') {
            Msg::add(__('admin.completed'), 'success');
        }
        return true;
    }

    public static function migrations()
    {
        return true;
    }
}
