<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Session;

class LogoutController extends Controller
{
    public function index()
    {
        Session::logout();
    }
}
