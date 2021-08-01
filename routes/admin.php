<?php

Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->module('admin', 'HomeController');

    Route::getType('post');
        Route::get('/space/ban')->module('admin', 'Controllers\SpacesController@delSpace');
        Route::get('/user/ban')->module('admin', 'Controllers\UsersController@banUser');
        Route::get('/favicon/add')->module('admin', 'Controllers\WebsController@favicon');
        Route::get('/word/del')->module('admin', 'Controllers\WordsController@deletes');
        Route::get('/audit/status')->module('admin', 'Controllers\AuditsController@status');
        
        Route::getProtect();
            Route::get('/badge/user/add')->module('admin', 'Controllers\UsersController@addBadgeUser');
            Route::get('/badge/add')->module('admin', 'Controllers\BadgesController@add');
            Route::get('/badge/edit/{id}')->module('admin', 'Controllers\BadgesController@edit')->where(['id' => '[0-9]+']);
            Route::get('/word/add')->module('admin', 'Controllers\WordsController@add');
            Route::get('/user/edit/{id}')->module('admin', 'Controllers\UsersController@userEdit')->where(['id' => '[0-9]+']);
            Route::get('/web/add')->module('admin', 'Controllers\WebsController@add');
            Route::get('/web/edit/{id}')->module('admin', 'Controllers\WebsController@edit')->where(['id' => '[0-9]+']);
            Route::get('/topic/add')->module('admin', 'Controllers\TopicsController@add');
            Route::get('/topic/edit/{id}')->module('admin', 'Controllers\TopicsController@edit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/users')->module('admin', 'Controllers\UsersController', ['all']);
    Route::get('/users/ban')->module('admin', 'Controllers\UsersController', ['ban']);
    Route::get('/users/{id}/edit')->module('admin', 'Controllers\UsersController@userEditPage')->where(['id' => '[0-9]+']);
    Route::get('/users/page/{page?}')->module('admin', 'Controllers\UsersController@users', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->module('admin', 'Controllers\UsersController@logsIp')->where(['ip' => '[0-9].+']);
  
    Route::get('/audits')->module('admin', 'Controllers\AuditsController', ['all']);
    Route::get('/audits/approved')->module('admin', 'Controllers\AuditsController', ['approved']);
   
    Route::get('/topics')->module('admin', 'Controllers\TopicsController', ['all']);
    Route::get('/topics/page/{page?}')->module('admin', 'Controllers\TopicsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/topics/add')->module('admin', 'Controllers\TopicsController@addPage');
    Route::get('/topics/{id}/edit')->module('admin', 'Controllers\TopicsController@editPage')->where(['id' => '[0-9]+']);
    Route::get('/update/count')->module('Controllers\TopicsController@updateQuantity'); 
   
    Route::get('/invitations')->module('admin', 'Controllers\InvitationsController', ['all']);
   
    Route::get('/spaces')->module('admin', 'Controllers\SpacesController', ['all']);
    Route::get('/spaces/ban')->module('admin', 'Controllers\SpacesController', ['ban']); 

    Route::get('/posts')->module('admin', 'Controllers\PostsController', ['all']);
    Route::get('/posts/page/{page?}')->module('admin', 'Controllers\PostsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/posts/ban')->module('admin', 'Controllers\PostsController', ['ban']); 

    Route::get('/comments')->module('admin', 'Controllers\CommentsController', ['all']);
    Route::get('/comments/page/{page?}')->module('admin', 'Controllers\CommentsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/comments/ban')->module('admin', 'Controllers\CommentsController', ['ban']); 

    Route::get('/answers')->module('admin', 'Controllers\AnswersController', ['all']);
    Route::get('/answers/page/{page?}')->module('admin', 'Controllers\AnswersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/answers/ban')->module('admin', 'Controllers\AnswersController', ['ban']); 

    Route::get('/badges')->module('admin', 'Controllers\BadgesController', ['all']);
    Route::get('/badges/add')->module('admin', 'Controllers\BadgesController@addPage');
    Route::get('/badges/{id}/edit')->module('admin', 'Controllers\BadgesController@editPage')->where(['id' => '[0-9]+']);
    Route::get('/badges/user/add/{id}')->module('admin', 'Controllers\BadgesController@addUserPage')->where(['id' => '[0-9]+']);
  
    Route::get('/webs')->module('admin', 'Controllers\WebsController', ['all']);
    Route::get('/webs/add')->module('admin', 'Controllers\WebsController@addPage', ['all']);
    Route::get('/webs/{id}/edit')->module('admin', 'Controllers\WebsController@editPage')->where(['id' => '[0-9]+']);
    
    Route::get('/words/add')->module('admin', 'Controllers\WordsController@addPage');
    Route::get('/words')->module('admin', 'Controllers\WordsController', ['all']);
Route::endGroup();