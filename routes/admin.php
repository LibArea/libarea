<?php

Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->controller('Admin\HomeController')->name('admin');

    Route::getType('post');
        Route::get('/space/ban')->controller('Admin\SpacesController@delSpace');
        Route::get('/user/ban')->controller('Admin\UsersController@banUser');
        Route::get('/favicon/add')->controller('Admin\WebsController@favicon');
        Route::get('/word/ban')->controller('Admin\WordsController@deletes');
        Route::get('/audit/status')->controller('Admin\AuditsController@status');
        Route::get('/reports/status')->controller('Admin\ReportsController@status');
        
        Route::getProtect();
            Route::get('/badge/user/add')->controller('Admin\BadgesController@addUser');
            Route::get('/badge/add')->controller('Admin\BadgesController@add');
            Route::get('/badge/edit/{id}')->controller('Admin\BadgesController@edit')->where(['id' => '[0-9]+']);
            Route::get('/word/add')->controller('Admin\WordsController@add');
            Route::get('/user/edit/{id}')->controller('Admin\UsersController@userEdit')->where(['id' => '[0-9]+']);
            Route::get('/web/add')->controller('Admin\WebsController@add');
            Route::get('/web/edit/{id}')->controller('Admin\WebsController@edit')->where(['id' => '[0-9]+']);
            Route::get('/topic/add')->controller('Admin\TopicsController@add');
            Route::get('/topic/edit/{id}')->controller('Admin\TopicsController@edit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/users')->controller('Admin\UsersController', ['all'])->name('admin.users');
    Route::get('/users/ban')->controller('Admin\UsersController', ['ban']);
    Route::get('/users/{id}/edit')->controller('Admin\UsersController@userEditPage')->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/page/{page?}')->controller('Admin\UsersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->controller('Admin\UsersController@logsIp', ['logs'])->where(['ip' => '[0-9].+']);
    Route::get('/regip/{ip}')->controller('Admin\UsersController@logsIp', ['reg'])->where(['ip' => '[0-9].+']);
    
    Route::get('/audits')->controller('Admin\AuditsController', ['all'])->name('admin.audits');
    Route::get('/audits/approved')->controller('Admin\AuditsController', ['approved']);
   
    Route::get('/topics')->controller('Admin\TopicsController', ['all'])->name('admin.topics');
    Route::get('/topics/page/{page?}')->controller('Admin\TopicsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/topics/add')->controller('Admin\TopicsController@addPage')->name('admin.topic.add');
    Route::get('/topics/{id}/edit')->controller('Admin\TopicsController@editPage')->where(['id' => '[0-9]+'])->name('admin.topic.edit');
    Route::get('/update/count')->controller('Admin\TopicsController@updateQuantity'); 
   
    Route::get('/invitations')->controller('Admin\InvitationsController', ['all'])->name('admin.invitations');
   
    Route::get('/spaces')->controller('Admin\SpacesController', ['all'])->name('admin.spaces');
    Route::get('/spaces/ban')->controller('Admin\SpacesController', ['ban']); 

    Route::get('/posts')->controller('Admin\PostsController', ['all'])->name('admin.posts');
    Route::get('/posts/page/{page?}')->controller('Admin\PostsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/posts/ban')->controller('Admin\PostsController', ['ban']); 

    Route::get('/comments')->controller('Admin\CommentsController', ['all'])->name('admin.comments');
    Route::get('/comments/page/{page?}')->controller('Admin\CommentsController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/comments/ban')->controller('Admin\CommentsController', ['ban']); 

    Route::get('/answers')->controller('Admin\AnswersController', ['all'])->name('admin.answers');
    Route::get('/answers/page/{page?}')->controller('Admin\AnswersController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/answers/ban')->controller('Admin\AnswersController', ['ban']); 

    Route::get('/badges')->controller('Admin\BadgesController', ['all'])->name('admin.badges');
    Route::get('/badges/add')->controller('Admin\BadgesController@addPage');
    Route::get('/badges/{id}/edit')->controller('Admin\BadgesController@editPage')->where(['id' => '[0-9]+']);
    Route::get('/badges/user/add/{id}')->controller('Admin\BadgesController@addUserPage')->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/webs')->controller('Admin\WebsController', ['all'])->name('admin.webs');
    Route::get('/webs/add')->controller('Admin\WebsController@addPage', ['all'])->name('link-add');
    Route::get('/webs/{id}/edit')->controller('Admin\WebsController@editPage')->where(['id' => '[0-9]+'])->name('link-edit');
    
    Route::get('/words/add')->controller('Admin\WordsController@addPage');
    Route::get('/words')->controller('Admin\WordsController', ['all'])->name('admin.words');
    
    Route::get('/reports')->controller('Admin\ReportsController')->name('admin.reports');
    Route::get('/reports/page/{page?}')->controller('Admin\ReportsController')->where(['page' => '[0-9]+']);
Route::endGroup();