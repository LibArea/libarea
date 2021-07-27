<?php

Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->module('admin', 'Controller');

    Route::getType('post');
        Route::get('/space/ban')->module('admin', 'Controller@delSpace');
        Route::get('/ban')->module('admin', 'Controller@banUser');
        Route::get('/favicon/add')->module('admin', 'Controller@favicon');
        Route::get('/word/del')->module('admin', 'Controller@deleteWord');
        Route::get('/audit/status')->module('admin', 'Controller@status');
        
        Route::getProtect();
            Route::get('/badge/user/add')->module('admin', 'Controller@addBadgeUser');
            Route::get('/badge/add')->module('admin', 'Controller@badgeAdd');
            Route::get('/word/add')->module('admin', 'Controller@wordAdd');
            Route::get('/user/edit/{id}')->module('admin', 'Controller@userEdit')->where(['id' => '[0-9]+']);
            Route::get('/domain/edit/{id}')->module('admin', 'Controller@domainEdit')->where(['id' => '[0-9]+']);
            Route::get('/badge/edit/{id}')->module('admin', 'Controller@badgeEdit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/{method}')->module('admin', 'Controller@<method>', ['all']);
    Route::get('/{method}/ban')->module('admin', 'Controller@<method>', ['ban']);

    Route::get('/users/page/{page?}')->module('admin', 'Controller@users', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/user/{id}/edit')->module('admin', 'Controller@userEditPage')->where(['id' => '[0-9]+']);
    Route::get('/logip/{ip}')->module('admin', 'Controller@logsIp')->where(['ip' => '[0-9].+']);
    Route::get('/words/add')->module('admin', 'Controller@wordsAddPage');
    Route::get('/audit/approved')->module('admin', 'Controller@audit', ['approved']);
    Route::get('/badge/add')->module('admin', 'Controller@addBadgePage');
    Route::get('/badge/user/add/{id}')->module('admin', 'Controller@addBadgeUserPage')->where(['id' => '[0-9]+']);
    Route::get('/badge/{id}/edit')->module('admin', 'Controller@editBadgePage')->where(['id' => '[0-9]+']);
Route::endGroup();