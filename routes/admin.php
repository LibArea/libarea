<?php

Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->controller('Admin\HomeController')->name('admin');

    Route::getType('post');
        Route::get('/test/mail')->controller('Admin\СonsoleController@testMail')->name('admin.test.mail');
        Route::get('/user/ban')->controller('Admin\UsersController@banUser');
        Route::get('/favicon/add')->controller('Admin\WebsController@favicon');
        Route::get('/word/ban')->controller('Admin\WordsController@deletes');
        Route::get('/audit/status')->controller('Admin\AuditsController@status');
        Route::get('/reports/status')->controller('Admin\ReportsController@status');
        
        Route::getProtect();
            Route::get('/badge/user/create')->controller('Admin\BadgesController@addUser')->name('admin.user.badge.create');
            Route::get('/badge/create')->controller('Admin\BadgesController@create')->name('admin.badge.create');
            Route::get('/badge/edit/{id}')->controller('Admin\BadgesController@edit')->where(['id' => '[0-9]+']);
            Route::get('/word/create')->controller('Admin\WordsController@create')->name('admin.word.create');
            Route::get('/user/edit/{id}')->controller('Admin\UsersController@userEdit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/tools')->controller('Admin\ToolsController')->name('admin.tools');
  
    Route::get('/users')->controller('Admin\UsersController', ['all'])->name('admin.users');
    Route::get('/users/ban')->controller('Admin\UsersController', ['ban'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->controller('Admin\UsersController@userEditPage')->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/page/{page?}')->controller('Admin\UsersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->controller('Admin\UsersController@logsIp', ['logs'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->controller('Admin\UsersController@logsIp', ['reg'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->controller('Admin\AuditsController', ['all'])->name('admin.audits');
    Route::get('/audits/approved')->controller('Admin\AuditsController', ['approved'])->name('admin.audits.approved');
   
    Route::get('/topics')->controller('Admin\TopicsController', ['all'])->name('admin.topics');
    Route::get('/topics/parent')->controller('Admin\TopicsController', ['parent'])->name('admin.parent');
    Route::get('/topics/page/{page?}')->controller('Admin\TopicsController', ['all'])->where(['page' => '[0-9]+']);
     
    Route::get('/update/count/topic')->controller('Admin\СonsoleController@updateCountPostTopic')->name('admin.count.topic'); 
    Route::get('/update/count/up')->controller('Admin\СonsoleController@updateCountUp')->name('admin.count.up');
   
    Route::get('/invitations')->controller('Admin\InvitationsController', ['all'])->name('admin.invitations');
   
    Route::get('/posts')->controller('Admin\PostsController', ['all'])->name('admin.posts');
    Route::get('/posts/page/{page?}')->controller('Admin\PostsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/posts/ban')->controller('Admin\PostsController', ['ban'])->name('admin.posts.ban'); 

    Route::get('/comments')->controller('Admin\CommentsController', ['all'])->name('admin.comments');
    Route::get('/comments/page/{page?}')->controller('Admin\CommentsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/comments/ban')->controller('Admin\CommentsController', ['ban'])->name('admin.comments.ban'); 

    Route::get('/answers')->controller('Admin\AnswersController', ['all'])->name('admin.answers');
    Route::get('/answers/page/{page?}')->controller('Admin\AnswersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/answers/ban')->controller('Admin\AnswersController', ['ban'])->name('admin.answers.ban'); 

    Route::get('/badges')->controller('Admin\BadgesController', ['all'])->name('admin.badges');
    Route::get('/badges/add')->controller('Admin\BadgesController@addPage')->name('admin.badges.add');
    Route::get('/badges/{id}/edit')->controller('Admin\BadgesController@editPage')->where(['id' => '[0-9]+'])->name('admin.badges.edit');
    Route::get('/badges/user/add/{id}')->controller('Admin\BadgesController@addUserPage')->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/webs')->controller('Admin\WebsController', ['all'])->name('admin.webs');
    
    Route::get('/words/add')->controller('Admin\WordsController@addPage')->name('admin.words.add');
    Route::get('/words')->controller('Admin\WordsController', ['all'])->name('admin.words');
    
    Route::get('/reports')->controller('Admin\ReportsController')->name('admin.reports');
    Route::get('/reports/page/{page?}')->controller('Admin\ReportsController')->where(['page' => '[0-9]+']);
Route::endGroup();