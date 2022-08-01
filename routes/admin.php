<?php

Route::prefix('/mod/admin');
Route::before('Designator', [UserData::REGISTERED_ADMIN, '='])->getGroup();

    Route::get('/')->module('admin', 'App\Home')->name('admin');

    Route::getType('post');
        Route::get('/test/mail')->module('admin', 'App\Console@testMail')->name('admin.test.mail');
        Route::get('/user/ban')->module('admin', 'App\Users@banUser');
        Route::get('/favicon/add')->module('admin', 'App\Webs@favicon');
        Route::get('/screenshot/add')->module('admin', 'App\Webs@screenshot');
        Route::get('/word/ban')->module('admin', 'App\Words@deletes');
        Route::get('/audit/status')->module('admin', 'App\Audits@status');
        Route::get('/reports/saw')->module('admin', 'App\Audits@saw');
        Route::get('/topic/ban')->module('admin', 'App\Facets@deletes');
        Route::get('/badge/remove')->module('admin', 'App\Badges@remove');
        Route::get('/manual/update')->module('admin', 'App\Console'); 
        
        Route::getProtect();
            Route::get('/badge/user/create')->module('admin', 'App\Badges@rewarding')->name('admin.user.badge.create');
            Route::get('/badge/create')->module('admin', 'App\Badges@create')->name('admin.badge.create');
            Route::get('/badge/edit/{id}')->module('admin', 'App\Badges@change')->name('admin.badge.change');
            Route::get('/word/create')->module('admin', 'App\Words@create')->name('admin.word.create');
            Route::get('/user/edit/{id}')->module('admin', 'App\Users@change')->name('admin.user.change');
        Route::endProtect();
    Route::endType();
  
    Route::get('/tools')->module('admin', 'App\Tools')->name('admin.tools');

    Route::get('/users/ban')->module('admin', 'App\Users', ['ban'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->module('admin', 'App\Users@edit', ['edit'])->name('admin.user.edit');
    Route::get('/users/{page?}')->module('admin', 'App\Users', ['all'])->name('admin.users');       
    Route::get('/logip/{ip}')->module('admin', 'App\Users@logsIp', ['logip'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->module('admin', 'App\Users@logsIp', ['regip'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->module('admin', 'App\Audits', ['all', 'audits'])->name('admin.audits');
    Route::get('/audits/approved')->module('admin', 'App\Audits', ['ban', 'audits'])->name('admin.audits.ban');
    Route::get('/report')->module('admin', 'App\Audits', ['all', 'reports'])->name('admin.reports');

    Route::get('/invitations')->module('admin', 'App\Invitations')->name('admin.invitations');
   
    Route::get('/answers/deleted/{page?}')->controller('Answer\AnswerController', ['deleted'])->name('answers.deleted');
    Route::get('/comments/deleted/{page?}')->controller('Comment\CommentController', ['deleted'])->name('comments.deleted');
    
    
    Route::get('/web/deleted/{page?}')->controller('Item\HomeController', ['deleted'])->name('web.deleted');
    Route::get('/web/audits/{page?}')->controller('Item\HomeController', ['audits'])->name('web.audits');

    Route::get('/badges')->module('admin', 'App\Badges')->name('admin.badges');
    Route::get('/badges/add')->module('admin', 'App\Badges@add')->name('admin.badges.add');
    Route::get('/badges/{id}/edit')->module('admin', 'App\Badges@edit')->name('admin.badges.edit');
    Route::get('/badges/user/add/{id}')->module('admin', 'App\Badges@addUser')->name('admin.badges.user.add');
  
    Route::get('/words/add')->module('admin', 'App\Words@add')->name('words.add');
    Route::get('/words')->module('admin', 'App\Words')->name('admin.words');
    
    Route::get('/facets')->module('admin', 'App\Facets')->name('admin.facets.all');
    Route::get('/facets/{type}')->module('admin', 'App\Facets@type')->where(['type' => '[a-z]+'])->name('admin.facets.type');
    Route::get('/facets/ban/{type}')->module('admin', 'App\Facets@ban')->where(['type' => '[a-z]+'])->name('admin.facets.ban.type');

    Route::get('/css')->module('admin', 'App\Css')->name('admin.css');
    Route::get('/logs/search')->module('admin', 'App\Logs@logsSearch')->name('admin.logs.search');
    Route::get('/logs/{page?}')->module('admin', 'App\Logs')->name('admin.logs');
 
    Route::get('/deleted/{page?}')->controller('HomeController', ['deleted'])->name('main.deleted'); 
 
Route::endGroup();
