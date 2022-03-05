<?php

Radjax\Route::get("/api-search/{?query}", ["post"], "Modules\Search\App\Search@api", ["protected" => false, "where" => ["query" => "[А-Яа-яa-zA-Z0-9]+"],  "session_saved" => false]);
