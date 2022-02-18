<?php

Route::prefix('/admin');
Route::before('Designator', [UserData::REGISTERED_ADMIN, '='])->getGroup();

    Route::get('/')->module('admin', 'App\Home')->name('admin');

    Route::getType('post');
        Route::get('/test/mail')->module('admin', 'App\Сonsole@testMail')->name('admin.test.mail');
        Route::get('/user/ban')->module('admin', 'App\Users@banUser');
        Route::get('/favicon/add')->module('admin', 'App\Webs@favicon');
        Route::get('/word/ban')->module('admin', 'App\Words@deletes');
        Route::get('/navigation/ban')->module('admin', 'App\Navigation@visibility');
        Route::get('/audit/status')->module('admin', 'App\Audits@status');
        Route::get('/reports/status')->module('admin', 'App\Reports@status');
        Route::get('/topic/ban')->module('admin', 'App\Facets@deletes');
        Route::get('/badge/remove')->module('admin', 'App\Badges@remove');
        
        Route::getProtect();
            Route::get('/badge/user/create')->module('admin', 'App\Badges@addUser')->name('admin.user.badge.create');
            Route::get('/badge/create')->module('admin', 'App\Badges@create')->name('admin.badge.create');
            Route::get('/badge/edit/{id}')->module('admin', 'App\Badges@edit')->where(['id' => '[0-9]+']);
            Route::get('/navigation/create')->module('admin', 'App\Navigation@create')->name('admin.navigation.create');
            Route::get('/navigation/edit')->module('admin', 'App\Navigation@edit')->name('admin.navigation.edit');
            Route::get('/word/create')->module('admin', 'App\Words@create')->name('admin.word.create');
            Route::get('/user/edit/{id}')->module('admin', 'App\Users@edit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/tools')->module('admin', 'App\Tools', ['tools.all', 'tools'])->name('admin.tools');
  
    Route::get('/users')->module('admin', 'App\Users', ['users.all', 'users'])->name('admin.users');
    Route::get('/users/ban')->module('admin', 'App\Users', ['users.ban', 'users'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->module('admin', 'App\Users@userEditPage', ['users.edit', 'users'])->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/page/{page?}')->module('admin', 'App\Users', ['users.all', 'users'])->where(['page' => '[0-9]+']);
    Route::get('/logip/{ip}')->module('admin', 'App\Users@logsIp', ['users.logip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->module('admin', 'App\Users@logsIp', ['users.regip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->module('admin', 'App\Audits', ['audits.all', 'audits'])->name('admin.audits');
    Route::get('/audits/approved')->module('admin', 'App\Audits', ['audits.ban', 'audits'])->name('admin.audits.ban');
   
    Route::get('/topics')->module('admin', 'App\Facets', ['topics.all', 'topics'])->name('admin.topics');
    Route::get('/topics/ban')->module('admin', 'App\Facets', ['topics.ban', 'topics'])->name('admin.topics.ban');
    Route::get('/topics/page/{page?}')->module('admin', 'App\Facets', ['topics.all', 'topics'])->where(['page' => '[0-9]+']);
     
    Route::get('/sections')->module('admin', 'App\Facets', ['sections.all', 'sections'])->name('admin.sections');     
     
    Route::get('/blogs')->module('admin', 'App\Facets', ['blogs.all', 'blogs'])->name('admin.blogs');
    Route::get('/blogs/ban')->module('admin', 'App\Facets', ['blogs.ban', 'blogs'])->name('admin.blogs.ban');
    
    Route::get('/pages')->module('admin', 'App\Facets@pages', ['pages.all', 'pages'])->name('admin.pages');
     
    Route::get('/update/count/topic')->module('admin', 'App\Сonsole@topic')->name('admin.count.topic'); 
    Route::get('/update/count/up')->module('admin', 'App\Сonsole@up')->name('admin.count.up');
    Route::get('/update/user/tl')->module('admin', 'App\Сonsole@tl')->name('admin.users.tl');
    Route::get('/update/build/css')->module('admin', 'App\Сonsole@css')->name('admin.build.css');    
    Route::get('/invitations')->module('admin', 'App\Invitations')->name('admin.invitations');
   
    Route::get('/answers/deleted')->controller('Answer\AnswerController', ['answers.deleted', 'answers'])->name('answers.deleted');
    Route::get('/answers/deleted/page/{page?}')->controller('Answer\AnswerController', ['answers.deleted', 'answers'])->where(['page' => '[0-9]+']);
    Route::get('/comments/deleted')->controller('Comment\CommentController', ['comments.deleted', 'comments'])->name('comments.deleted');
    Route::get('/comments/deleted/page/{page?}')->controller('Comment\CommentController', ['comments.deleted', 'comments'])->where(['page' => '[0-9]+']);
    Route::get('/web/deleted')->module('catalog', 'App\Home', ['web.deleted', 'web'])->name('web.deleted');
    Route::get('/web/deleted/page/{page?}')->module('catalog', 'App\Home', ['web.deleted', 'web'])->where(['page' => '[0-9]+']);
    Route::get('/web/audits')->module('catalog', 'App\Home', ['web.audits', 'web'])->name('web.audits');
    Route::get('/web/audits/page/{page?}')->module('catalog', 'App\Home', ['web.audits', 'web'])->where(['page' => '[0-9]+']);
    
    Route::get('/badges')->module('admin', 'App\Badges', ['badges.all', 'badges'])->name('admin.badges');
    Route::get('/badges/add')->module('admin', 'App\Badges@addPage', ['add', 'badges'])->name('badges.add');
    Route::get('/badges/{id}/edit')->module('admin', 'App\Badges@editPage', ['badges.edit', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.edit');
    Route::get('/badges/user/add/{id}')->module('admin', 'App\Badges@addUserPage', ['add', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/sites')->module('admin', 'App\Webs', ['web', 'sites'])->name('admin.sites');
    Route::get('/sites/page/{page?}')->module('admin', 'App\Webs', ['sites.all', 'sites'])->where(['page' => '[0-9]+']);
    Route::get('/words/add')->module('admin', 'App\Words@addPage', ['add', 'words'])->name('words.add');
    Route::get('/words')->module('admin', 'App\Words', ['words.all', 'words'])->name('admin.words');
    
    Route::get('/reports')->module('admin', 'App\Reports', ['reports.all', 'reports'])->name('admin.reports');
    Route::get('/reports/page/{page?}')->module('admin', 'App\Reports', ['reports.all', 'reports'])->where(['page' => '[0-9]+']);
    
    Route::get('/structure')->module('admin', 'App\TreeFacet', ['all'])->name('admin.all.structure');
    Route::get('/structure/topic')->module('admin', 'App\TreeFacet', ['topic'])->name('admin.topic.structure');
    Route::get('/structure/blog')->module('admin', 'App\TreeFacet', ['blog'])->name('admin.blog.structure');
    Route::get('/structure/section')->module('admin', 'App\TreeFacet', ['section'])->name('admin.section.structure');
    Route::get('/structure/category')->module('admin', 'App\TreeFacet', ['category'])->name('admin.category.structure');

    Route::get('/navigation')->module('admin', 'App\Navigation')->name('admin.navigation');
    Route::get('/navigation/sub/{id}')->module('admin', 'App\Navigation@subPage')->where(['id' => '[0-9]+'])->name('admin.navigation.sub');
    Route::get('/navigation/{id}/add')->module('admin', 'App\Navigation@addPage')->where(['id' => '[0-9]+'])->name('admin.navigation.add');
    
    Route::get('/css')->module('admin', 'App\Home@css')->name('admin.css');
    Route::get('/info')->module('admin', 'App\Home@css')->name('admin.info');
    
    Route::get('/logs')->module('admin', 'App\Reports@logs', ['logs.all', 'logs'])->name('admin.logs');
    Route::get('/logs/page/{page?}')->module('admin', 'App\Reports@logs', ['logs.all', 'logs'])->where(['page' => '[0-9]+']);
Route::endGroup();
