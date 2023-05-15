<?php

Route::before('Designator', [UserData::USER_FIRST_LEVEL, '>='])->getGroup();
    Route::post('/web/favicon/add')->controller('Item\ImgController@favicon');
    Route::post('/web/screenshot/add')->controller('Item\ImgController@screenshot');
    Route::get('/web/bookmarks')->controller('Item\UserAreaController@bookmarks')->name('web.bookmarks');
    Route::get('/web/my')->controller('Item\UserAreaController')->name('web.user.sites');
Route::endGroup();

Route::get('/web')->controller('Item\HomeController', ['main'])->name('web');
Route::get('/web/website/{id}/{slug?}')->controller('Item\DetailedController')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-.]+'])->name('website');
Route::get('/web/dir/{sort}/{slug}')->controller('Item\DirController')->name('category');
Route::get('/web/{grouping}/dir/{sort}/{slug}')->controller('Item\DirController')->name('grouping.category');
Route::post('/search/web/url')->controller('Item\AddItemController@searchUrl');
