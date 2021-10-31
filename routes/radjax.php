<?php

Radjax\Route::get("/test-search/{?query}", ["get","post"], "App\Controllers\TestController", ["protected"=>false, "where"=>["query" => "[А-Яа-яa-zA-Z0-9]+"],  "session_saved" => false]);