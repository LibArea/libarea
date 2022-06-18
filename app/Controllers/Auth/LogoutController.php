<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;

class LogoutController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        setcookie("remember", "", time() - 3600, "/");
        redirect('/');
    }
}
