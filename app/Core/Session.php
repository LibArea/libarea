<?php

class Session
{
    public static function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        setcookie("remember", "", time() - 3600, "/", httponly: true);

        redirect('/');
    }
}
