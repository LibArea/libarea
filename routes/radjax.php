<?php

Radjax\Route::get("/search", ["post"], "Modules\Search\App\Search@api", ["protected" => false, "session_saved" => false]);
 
$access = 'App\Middleware\Before\UserAuth@index'; 
Radjax\Route::get("/team/action", ["post"], "Modules\Teams\App\Teams@action", ["protected" => false, "before" => $access]);
Radjax\Route::get("/favorite", ["post"], "App\Controllers\FavoriteController@index", ["protected" => false, "before" => $access]);
Radjax\Route::get("/post/profile", ["post"], "App\Controllers\Post\PostController@postProfile", ["protected" => false, "before" => $access]);
