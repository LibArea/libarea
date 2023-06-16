<?php

// Website directory
// Каталог сайтов

Route::before('Designator', [UserData::REGISTERED_ADMIN, '='])->getGroup();
    Route::get('/web/deleted')->controller('Item\AdminController@deleted')->name('web.deleted');
    Route::get('/web/audits')->controller('Item\AdminController@audits')->name('web.audits');
    Route::get('/web/comments')->controller('Item\AdminController@comments')->name('web.comments');
    Route::get('/web/status/{code?}')->controller('Item\AdminController@status')->where(['code' => '[0-9]+'])->name('web.status');
Route::endGroup();

Route::before('Designator', [UserData::USER_FIRST_LEVEL, '>='])->getGroup();
    Route::post('/web/favicon/add')->controller('Item\ImgController@favicon');
    Route::post('/web/screenshot/add')->controller('Item\ImgController@screenshot');
    Route::post('/web/status/update')->controller('Item\AdminController@updateStatus');
    Route::get('/web/bookmarks')->controller('Item\UserAreaController@bookmarks')->name('web.bookmarks');
    Route::get('/web/my')->controller('Item\UserAreaController')->name('web.user.sites');
Route::endGroup();

Route::get('/web')->controller('Item\HomeController')->name('web');
Route::get('/web/website/{id}/{slug?}')->controller('Item\DetailedController')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-.]+'])->name('website');
Route::get('/web/dir/{sort}/{slug}')->controller('Item\DirController')->name('category');
Route::get('/web/{grouping}/dir/{sort}/{slug}')->controller('Item\DirController')->name('grouping.category');
Route::post('/search/web/url')->controller('Item\AddItemController@searchUrl');
