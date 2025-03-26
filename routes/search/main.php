<?php

use Modules\Search\Controllers\SearchController;

Route::get('/search/go')->module('search', SearchController::class, 'go')->name('search.go');
Route::get('/search/opensearch')->module('search', SearchController::class, 'openSearch')->name('opensearch');
Route::post('/search/api')->module('search', SearchController::class, 'api');

Route::get('/search')->module('search', SearchController::class, 'index')->name('search');