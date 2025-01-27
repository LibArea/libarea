<?php

use Modules\Search\Controllers\SearchController;


Route::get('/search/go')->module('search', SearchController::class, 'go')->name('search.go');
Route::get('/search/opensearch')->module('search', SearchController::class, 'openSearch')->name('opensearch');
Route::post('/search/api')->module('search', SearchController::class, 'api');

Route::get('/search')->module('search', SearchController::class, 'index')->name('search');

/*
Route::get('/search')->controller(SearchController::class, 'index', ['post'])->name('search');
Route::get('/search/go')->controller(SearchController::class, 'go', ['post'])->protect()->name('search.go');


Route::post('/search/api')->controller(SearchController::class, 'api');
Route::get('/search/opensearch')->controller(SearchController::class, 'openSearch')->name('opensearch'); */