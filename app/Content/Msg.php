<?php

declare(strict_types=1);

use Hleb\Reference\RedirectInterface;

class Msg
{
    public static function get()
    {
        $msg = $_SESSION['msg'] ?? false;
        unset($_SESSION['msg']);

        $html = '';
        if ($msg) {
            $options = "3500, { valign: 'top', align: 'right' }";

            if ($msg['status'] == 'error') {
                $options = "3500, { valign: 'top', align: 'right', styles: { backgroundColor: 'red', fontSize: '18px' } }";
            }

            $html .= "Notice('" . $msg['msg'] . "', $options);";
        }

        return $html;
    }

    public static function add($msg, $status = 'error')
    {
        $_SESSION['msg'] = ['msg' => $msg, 'status' => $status];
    }

    public static function redirect(string $text, string $status, string $redirect = '/')
    {
        self::add($text, $status);

        redirect($redirect);
    }
}
