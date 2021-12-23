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
        Route::get('/topic/ban')->controller('Admin\FacetsController@deletes');
        Route::get('/badge/remove')->controller('Admin\BadgesController@remove');
        
        Route::getProtect();
            Route::get('/badge/user/create')->controller('Admin\BadgesController@addUser')->name('admin.user.badge.create');
            Route::get('/badge/create')->controller('Admin\BadgesController@create')->name('admin.badge.create');
            Route::get('/badge/edit/{id}')->controller('Admin\BadgesController@edit')->where(['id' => '[0-9]+']);
            Route::get('/word/create')->controller('Admin\WordsController@create')->name('admin.word.create');
            Route::get('/user/edit/{id}')->controller('Admin\UsersController@edit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/tools')->controller('Admin\ToolsController', ['tools.all', 'tools'])->name('admin.tools');
  
    Route::get('/users')->controller('Admin\UsersController', ['users.all', 'users'])->name('admin.users');
    Route::get('/users/ban')->controller('Admin\UsersController', ['users.ban', 'users'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->controller('Admin\UsersController@userEditPage', ['users.edit', 'users'])->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/page/{page?}')->controller('Admin\UsersController', ['users.all', 'users'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->controller('Admin\UsersController@logsIp', ['users.logip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->controller('Admin\UsersController@logsIp', ['users.regip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->controller('Admin\AuditsController', ['audits.all', 'audits'])->name('admin.audits');
    Route::get('/audits/approved')->controller('Admin\AuditsController', ['audits.ban', 'audits'])->name('admin.audits.ban');
   
    Route::get('/topics')->controller('Admin\FacetsController', ['topics.all', 'topics'])->name('admin.topics');
    Route::get('/topics/ban')->controller('Admin\FacetsController', ['topics.ban', 'topics'])->name('admin.topics.ban');
    Route::get('/topics/page/{page?}')->controller('Admin\FacetsController', ['topics.all', 'topics'])->where(['page' => '[0-9]+']);
     
    Route::get('/sections')->controller('Admin\FacetsController', ['sections.all', 'sections'])->name('admin.sections');     
     
    Route::get('/blogs')->controller('Admin\FacetsController', ['blogs.all', 'blogs'])->name('admin.blogs');
    Route::get('/blogs/ban')->controller('Admin\FacetsController', ['blogs.ban', 'blogs'])->name('admin.blogs.ban');
     
    Route::get('/update/count/topic')->controller('Admin\СonsoleController@topic')->name('admin.count.topic'); 
    Route::get('/update/count/up')->controller('Admin\СonsoleController@up')->name('admin.count.up');
    Route::get('/update/user/tl')->controller('Admin\СonsoleController@tl')->name('admin.users.tl');
    Route::get('/update/build/css')->controller('Admin\СonsoleController@css')->name('admin.build.css');    
    Route::get('/invitations')->controller('Admin\InvitationsController')->name('admin.invitations');
   
    Route::get('/posts')->controller('Admin\PostsController', ['posts.all', 'posts'])->name('admin.posts');
    Route::get('/posts/page/{page?}')->controller('Admin\PostsController', ['posts.all', 'posts'])->where(['page' => '[0-9]+']);
    Route::get('/posts/ban')->controller('Admin\PostsController', ['posts.ban', 'posts'])->name('admin.posts.ban'); 

    Route::get('/comments')->controller('Admin\CommentsController', ['comments.all', 'comments'])->name('admin.comments');
    Route::get('/comments/page/{page?}')->controller('Admin\CommentsController', ['comments.all', 'comments'])->where(['page' => '[0-9]+']);
    Route::get('/comments/ban')->controller('Admin\CommentsController', ['comments.ban', 'comments'])->name('admin.comments.ban'); 

    Route::get('/answers')->controller('Admin\AnswersController', ['answers.all', 'answers'])->name('admin.answers');
    Route::get('/answers/page/{page?}')->controller('Admin\AnswersController', ['answers.all', 'answers'])->where(['page' => '[0-9]+']);
    Route::get('/answers/ban')->controller('Admin\AnswersController', ['answers.ban', 'answers'])->name('admin.answers.ban'); 

    Route::get('/badges')->controller('Admin\BadgesController', ['badges.all', 'badges'])->name('admin.badges');
    Route::get('/badges/add')->controller('Admin\BadgesController@addPage', ['add', 'badges'])->name('badges.add');
    Route::get('/badges/{id}/edit')->controller('Admin\BadgesController@editPage', ['badges.edit', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.edit');
    Route::get('/badges/user/add/{id}')->controller('Admin\BadgesController@addUserPage', ['add', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/webs')->controller('Admin\WebsController', ['sites.all', 'sites'])->name('admin.sites');
    
    Route::get('/words/add')->controller('Admin\WordsController@addPage', ['add', 'words'])->name('words.add');
    Route::get('/words')->controller('Admin\WordsController', ['words.all', 'words'])->name('admin.words');
    
    Route::get('/reports')->controller('Admin\ReportsController', ['reports.all', 'reports'])->name('admin.reports');
    Route::get('/reports/page/{page?}')->controller('Admin\ReportsController', ['reports.ban', 'reports'])->where(['page' => '[0-9]+']);
Route::endGroup();