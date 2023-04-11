<?php

Route::prefix('/mod/admin');
Route::before('Designator', [UserData::REGISTERED_ADMIN, '='])->getGroup();

    Route::get('/')->module('admin', 'App\Home')->name('admin');

    Route::post('/test/mail')->module('admin', 'App\Console@testMail')->name('admin.test.mail');
    Route::post('/user/ban')->module('admin', 'App\Users@banUser');
    Route::post('/favicon/add')->module('admin', 'App\Webs@favicon');
    Route::post('/screenshot/add')->module('admin', 'App\Webs@screenshot');
    Route::post('/word/ban')->module('admin', 'App\Words@deletes');
    Route::post('/audit/status')->module('admin', 'App\Audits@statusApproved');
    Route::post('/reports/saw')->module('admin', 'App\Audits@saw');
    Route::post('/topic/ban')->module('admin', 'App\Facets@deletes');
    Route::post('/badge/remove')->module('admin', 'App\Badges@remove');
    Route::post('/manual/update')->module('admin', 'App\Console'); 
    
    Route::getProtect();
        Route::post('/badge/user/create')->module('admin', 'App\Badges@rewarding')->name('admin.user.badge.create');
        Route::post('/badge/create')->module('admin', 'App\Badges@create')->name('admin.badge.create');
        Route::post('/badge/edit/{id}')->module('admin', 'App\Badges@change')->name('admin.badge.change');
        Route::post('/word/create')->module('admin', 'App\Words@create')->name('admin.word.create');
        Route::post('/user/edit/{id}')->module('admin', 'App\Users@change')->name('admin.user.change');
        Route::post('/setting/edit')->module('admin', 'App\Setting@change')->name('admin.setting.change');
    Route::endProtect();
  
    Route::get('/tools')->module('admin', 'App\Tools')->name('admin.tools');

    Route::get('/users/ban')->module('admin', 'App\Users', ['ban'])->name('admin.users.ban');
    Route::get('/users/{id}/edit')->module('admin', 'App\Users@edit', ['edit'])->name('admin.user.edit');
    Route::get('/users')->module('admin', 'App\Users', ['all'])->name('admin.users');       
    Route::get('/logip/{ip}')->module('admin', 'App\Users@logsIp', ['logip'])->where(['ip' => '[0-9].+'])->name('admin.logip');
    Route::get('/regip/{ip}')->module('admin', 'App\Users@logsIp', ['regip'])->where(['ip' => '[0-9].+'])->name('admin.regip');
    
    Route::get('/audits')->module('admin', 'App\Audits', ['all', 'audits'])->name('admin.audits');
    Route::get('/audits/approved')->module('admin', 'App\Audits', ['ban', 'audits'])->name('admin.audits.ban');
    Route::get('/report')->module('admin', 'App\Audits', ['all', 'reports'])->name('admin.reports');

    Route::get('/invitations')->module('admin', 'App\Invitations')->name('admin.invitations');
    
    Route::get('/setting')->module('admin', 'App\Setting', ['settings'])->name('admin.settings.general');
    Route::get('/setting/interface')->module('admin', 'App\Setting', ['interface'])->name('admin.settings.interface');
    Route::get('/setting/advertising')->module('admin', 'App\Setting', ['advertising'])->name('admin.settings.advertising');
   
    Route::get('/comments/deleted')->controller('Comment\CommentController', ['deleted'])->name('comments.deleted');
    
    Route::get('/web/deleted')->controller('Item\HomeController', ['deleted'])->name('web.deleted');
    Route::get('/web/audits')->controller('Item\HomeController', ['audits'])->name('web.audits');

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
    Route::get('/logs')->module('admin', 'App\Logs')->name('admin.logs');
 
    Route::get('/deleted')->controller('HomeController', ['deleted'])->name('main.deleted'); 
 
Route::endGroup();
