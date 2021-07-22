<?php
Route::prefix('/admin');
Route::before('Authorization@admin')->getGroup();
    Route::get('/')->controller('AdminController');

    Route::getType('post');
        Route::get('/space/ban')->controller('AdminController@delSpace');
        Route::get('/ban')->controller('AdminController@banUser');
        Route::get('/favicon/add')->controller('Web\WebController@favicon');
        Route::get('/word/del')->controller('AdminController@deleteWord');
        Route::get('/audit/status')->controller('AdminController@status');
        
        Route::getProtect();
            Route::get('/badge/user/add')->controller('AdminController@addBadgeUser');
            Route::get('/badge/add')->controller('AdminController@badgeAdd');
            Route::get('/word/add')->controller('AdminController@wordAdd');
            Route::get('/user/edit/{id}')->controller('AdminController@userEdit')->where(['id' => '[0-9]+']);
            Route::get('/domain/edit/{id}')->controller('AdminController@domainEdit')->where(['id' => '[0-9]+']);
            Route::get('/badge/edit/{id}')->controller('AdminController@badgeEdit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
  
    Route::get('/{method}')->controller('AdminController@<method>', ['all']);
    Route::get('/{method}/ban')->controller('AdminController@<method>', ['ban']);

    Route::get('/users/page/{page?}')->controller('AdminController@users', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/user/{id}/edit')->controller('AdminController@userEditPage')->where(['id' => '[0-9]+']);
    Route::get('/logip/{ip}')->controller('AdminController@logsIp')->where(['ip' => '[0-9].+']);
    Route::get('/words/add')->controller('AdminController@wordsAddForm');
    Route::get('/audit/approved')->controller('AdminController@audit', ['approved']);
    Route::get('/badge/add')->controller('AdminController@addBadgeForm');
    Route::get('/badge/user/add/{id}')->controller('AdminController@addBadgeUserForm')->where(['id' => '[0-9]+']);
    Route::get('/badge/{id}/edit')->controller('AdminController@editBadgeForm')->where(['id' => '[0-9]+']);
Route::endGroup();