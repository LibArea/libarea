<?php

Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->module('admin', 'HomeController')->name('admin');

    Route::getType('post');
        Route::get('/space/ban')->module('admin', 'Controllers\SpacesController@delSpace');
        Route::get('/user/ban')->module('admin', 'Controllers\UsersController@banUser');
        Route::get('/favicon/add')->module('admin', 'Controllers\WebsController@favicon');
        Route::get('/word/ban')->module('admin', 'Controllers\WordsController@deletes');
        Route::get('/audit/status')->module('admin', 'Controllers\AuditsController@status');
        Route::get('/reports/status')->module('admin', 'Controllers\ReportsController@status');
        
        Route::getProtect();
            Route::get('/badge/user/add')->module('admin', 'Controllers\BadgesController@addUser');
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
  
    Route::get('/users')->module('admin', 'Controllers\UsersController', ['all'])->name('admin.users');
    Route::get('/users/ban')->module('admin', 'Controllers\UsersController', ['ban']);
    Route::get('/users/{id}/edit')->module('admin', 'Controllers\UsersController@userEditPage')->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/page/{page?}')->module('admin', 'Controllers\UsersController@users', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->module('admin', 'Controllers\UsersController@logsIp')->where(['ip' => '[0-9].+']);
  
    Route::get('/audits')->module('admin', 'Controllers\AuditsController', ['all'])->name('admin.audits');
    Route::get('/audits/approved')->module('admin', 'Controllers\AuditsController', ['approved']);
   
    Route::get('/topics')->module('admin', 'Controllers\TopicsController', ['all'])->name('admin.topics');
    Route::get('/topics/page/{page?}')->module('admin', 'Controllers\TopicsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/topics/add')->module('admin', 'Controllers\TopicsController@addPage')->name('admin.topic.add');
    Route::get('/topics/{id}/edit')->module('admin', 'Controllers\TopicsController@editPage')->where(['id' => '[0-9]+'])->name('admin.topic.edit');
    Route::get('/update/count')->module('admin', 'Controllers\TopicsController@updateQuantity'); 
   
    Route::get('/invitations')->module('admin', 'Controllers\InvitationsController', ['all'])->name('admin.invitations');
   
    Route::get('/spaces')->module('admin', 'Controllers\SpacesController', ['all'])->name('admin.spaces');
    Route::get('/spaces/ban')->module('admin', 'Controllers\SpacesController', ['ban']); 

    Route::get('/posts')->module('admin', 'Controllers\PostsController', ['all'])->name('admin.posts');
    Route::get('/posts/page/{page?}')->module('admin', 'Controllers\PostsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/posts/ban')->module('admin', 'Controllers\PostsController', ['ban']); 

    Route::get('/comments')->module('admin', 'Controllers\CommentsController', ['all'])->name('admin.comments');
    Route::get('/comments/page/{page?}')->module('admin', 'Controllers\CommentsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/comments/ban')->module('admin', 'Controllers\CommentsController', ['ban']); 

    Route::get('/answers')->module('admin', 'Controllers\AnswersController', ['all'])->name('admin.answers');
    Route::get('/answers/page/{page?}')->module('admin', 'Controllers\AnswersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/answers/ban')->module('admin', 'Controllers\AnswersController', ['ban']); 

    Route::get('/badges')->module('admin', 'Controllers\BadgesController', ['all'])->name('admin.badges');
    Route::get('/badges/add')->module('admin', 'Controllers\BadgesController@addPage');
    Route::get('/badges/{id}/edit')->module('admin', 'Controllers\BadgesController@editPage')->where(['id' => '[0-9]+']);
    Route::get('/badges/user/add/{id}')->module('admin', 'Controllers\BadgesController@addUserPage')->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/webs')->module('admin', 'Controllers\WebsController', ['all'])->name('admin.webs');
    Route::get('/webs/add')->module('admin', 'Controllers\WebsController@addPage', ['all'])->name('link-add');
    Route::get('/webs/{id}/edit')->module('admin', 'Controllers\WebsController@editPage')->where(['id' => '[0-9]+'])->name('link-edit');
    
    Route::get('/words/add')->module('admin', 'Controllers\WordsController@addPage');
    Route::get('/words')->module('admin', 'Controllers\WordsController', ['all'])->name('admin.words');
    
    Route::get('/reports')->module('admin', 'Controllers\ReportsController')->name('admin.reports');
    Route::get('/reports/page/{page?}')->module('admin', 'Controllers\ReportsController')->where(['page' => '[0-9]+']);
Route::endGroup();