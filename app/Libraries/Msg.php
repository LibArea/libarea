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
                $html .= "Notice('" . $msg['msg'] . "', 3500, { valign: 'bottom',align: 'right', styles : {backgroundColor: 'red',fontSize: '18px'}});";
            else :
                $html .= "Notice('" . $msg['msg'] . "', 3500, { valign: 'bottom',align: 'right'});";
            endif;
        }

        return $html;
    }

    public static function add($msg, $status)
    {
        $_SESSION['msg'] = ['msg' => $msg, 'status' =>  $status ?? 'error'];
    }
}
