<?php

class Session
{
    public static function logout()
    {
       /* if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy(); */
		
		// https://www.php.net/manual/en/function.session-status.php
		if (session_status() === PHP_SESSION_ACTIVE)
			session_destroy();
		
        setcookie("remember", "", time() - 3600, "/", httponly: true);

        redirect('/');
    }
}
