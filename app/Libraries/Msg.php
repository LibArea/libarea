<?php

class Msg
{
    public static function get()
    {
        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        unset($_SESSION['msg']);

        $html = '';
        if ($msg) {
            if ($msg['status'] == 'error') :
                $html .= "Notiflix.Notify.failure('" . $msg['msg'] . "')";
            else :
                $html .= "Notiflix.Notify.info('" . $msg['msg'] . "')";
            endif;
        }

        return $html;
    }

    public static function add($msg, $status)
    {
        $_SESSION['msg'] = ['msg' => $msg, 'status' =>  $status ?? 'error'];
    }
}
