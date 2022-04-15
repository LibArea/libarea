<?php

Radjax\Route::get("/search", ["post"], "Modules\Search\App\Search@api", ["protected" => false, "session_saved" => false]);
 
$access = 'App\Middleware\Before\UserAuth@index'; 
Radjax\Route::get("/team/action", ["post"], "Modules\Teams\App\Teams@action", ["protected" => false, "before" => $access]);
Radjax\Route::get("/favorite", ["post"], "App\Controllers\FavoriteController@index", ["protected" => false, "before" => $access]);
Radjax\Route::get("/post/profile", ["post"], "App\Controllers\Post\PostController@postProfile", ["protected" => false, "before" => $access]);
Radjax\Route::get("/post/recommend", ["post"], "App\Controllers\Post\AddPostController@recommend", ["protected" => false, "before" => $access]);

Radjax\Route::get("/focus", ["post"], "App\Controllers\SubscriptionController@index", ["protected" => false, "before" => $access]);

Radjax\Route::get("/folder/content/del", ["post"], "App\Controllers\FolderController@delFolderContent", ["protected" => false, "before" => $access]);
Radjax\Route::get("/folder/del", ["post"], "App\Controllers\FolderController@delFolder", ["protected" => false, "before" => $access]);

Radjax\Route::get("/reply/editform", ["post"], "Modules\Catalog\App\Reply@index", ["protected" => false, "before" => $access]);
