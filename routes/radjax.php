<?php

Radjax\Route::get("/api-search/{?query}", ["post"], "Modules\Search\App\Search@api", ["protected" => true, "where" => ["query" => "[А-Яа-яa-zA-Z0-9]+"],  "session_saved" => false]);

Radjax\Route::get("/topics.json", ["get"], "App\Controllers\Api\ApiController@topics", ["protected" => false, "session_saved" => false]);

Radjax\Route::get("/links.json", ["get"], "App\Controllers\Api\ApiController@items", ["protected" => false, "session_saved" => false]);
