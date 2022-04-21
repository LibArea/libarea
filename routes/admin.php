<?php

Route::prefix('/mod/admin');
Route::before('Designator', [UserData::REGISTERED_ADMIN, '='])->getGroup();

    Route::get('/')->module('admin', 'App\Home')->name('admin');

    Route::getType('post');
        Route::get('/test/mail')->module('admin', 'App\Console@testMail')->name('admin.test.mail');
        Route::get('/user/ban')->module('admin', 'App\Users@banUser');
        Route::get('/favicon/add')->module('admin', 'App\Webs@favicon');
        Route::get('/word/ban')->module('admin', 'App\Words@deletes');
        Route::get('/audit/status')->module('admin', 'App\Audits@status');
        Route::get('/reports/saw')->module('admin', 'App\Audits@saw');
        Route::get('/topic/ban')->module('admin', 'App\Facets@deletes');
        Route::get('/badge/remove')->module('admin', 'App\Badges@remove');
        Route::get('/manual/update')->module('admin', 'App\Console'); 
        
        Route::getProtect();
            Route::get('/search/remove')->module('admin', 'App\Search@remove')->name('admin.search.remove');
            Route::get('/search/search')->module('admin', 'App\Search@search')->name('admin.search.search');
            Route::get('/search/edit')->module('admin', 'App\Search@edit')->name('admin.search.edit');
            
            Route::get('/badge/user/create')->module('admin', 'App\Badges@addUser')->name('admin.user.badge.create');
            Route::get('/badge/create')->module('admin', 'App\Badges@create')->name('admin.badge.create');
            Route::get('/badge/edit/{id}')->module('admin', 'App\Badges@edit')->where(['id' => '[0-9]+'])->name('admin.badge.change');
            Route::get('/word/create')->module('admin', 'App\Words@create')->name('admin.word.create');
            Route::get('/user/edit/{id}')->module('admin', 'App\Users@edit')->where(['id' => '[0-9]+'])->name('admin.user.change');
        Route::endProtect();
    Route::endType();
  
    Route::get('/tools')->module('admin', 'App\Tools', ['tools.all', 'tools'])->name('admin.tools');

    Route::get('/search')->module('admin', 'App\Search')->name('admin.search');
    Route::get('/search/query')->module('admin', 'App\Search@query')->name('admin.search.query');
    Route::get('/search/edit/{id}')->module('admin', 'App\Search@editForm')->where(['ip' => '[0-9].+'])->name('admin.search.edit.form');
    Route::get('/search/schemas')->module('admin', 'App\Search@schemas')->name('admin.search.schemas');

    Route::get('/users/ban')->module('admin', 'App\Users', ['users.ban', 'users'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->module('admin', 'App\Users@userEditPage', ['users.edit', 'users'])->where(['id' => '[0-9]+'])->name('admin.user.edit');
    Route::get('/users/{page?}')->module('admin', 'App\Users', ['users.all', 'users'])->name('admin.users');       
    
    Route::get('/logip/{ip}')->module('admin', 'App\Users@logsIp', ['users.logip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->module('admin', 'App\Users@logsIp', ['users.regip', 'users'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->module('admin', 'App\Audits', ['audits.all', 'audits'])->name('admin.audits');
    Route::get('/audits/approved')->module('admin', 'App\Audits', ['audits.ban', 'audits'])->name('admin.audits.ban');
    Route::get('/report')->module('admin', 'App\Audits', ['reports.all', 'reports'])->name('admin.reports');

    Route::get('/invitations')->module('admin', 'App\Invitations')->name('admin.invitations');
   
    Route::get('/answers/deleted/{page?}')->controller('Answer\AnswerController', ['answers.deleted', 'answers'])->name('answers.deleted');
    Route::get('/comments/deleted/{page?}')->controller('Comment\CommentController', ['comments.deleted', 'comments'])->name('comments.deleted');
    Route::get('/web/deleted/{page?}')->module('catalog', 'App\Home', ['web.deleted', 'web'])->name('web.deleted');
    Route::get('/web/audits/{page?}')->module('catalog', 'App\Home', ['web.audits', 'web'])->name('web.audits');

    Route::get('/badges')->module('admin', 'App\Badges', ['badges.all', 'badges'])->name('admin.badges');
    Route::get('/badges/add')->module('admin', 'App\Badges@addPage', ['add', 'badges'])->name('badges.add');
    Route::get('/badges/{id}/edit')->module('admin', 'App\Badges@editPage', ['badges.edit', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.edit');
    Route::get('/badges/user/add/{id}')->module('admin', 'App\Badges@addUserPage', ['add', 'badges'])->where(['id' => '[0-9]+'])->name('admin.badges.user.add');
  
    Route::get('/words/add')->module('admin', 'App\Words@addPage', ['add', 'words'])->name('words.add');
    Route::get('/words')->module('admin', 'App\Words', ['words.all', 'words'])->name('admin.words');
    
    Route::get('/facets')->module('admin', 'App\Facets')->name('admin.facets.all');
    Route::get('/facets/{type}')->module('admin', 'App\Facets@type')->where(['type' => '[a-z]+'])->name('admin.facets.type');
    Route::get('/facets/ban/{type}')->module('admin', 'App\Facets@ban')->where(['type' => '[a-z]+'])->name('admin.facets.ban.type');

    Route::get('/css')->module('admin', 'App\Home@css')->name('admin.css');
    Route::get('/logs/search')->module('admin', 'App\Audits@logsSearch', ['logssearch.all', 'logs'])->name('admin.logs.search');
    Route::get('/logs/{page?}')->module('admin', 'App\Audits@logs', ['logs.all', 'logs'])->name('admin.logs');
 
Route::endGroup();
