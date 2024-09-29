<?php

// Website directory
// Каталог сайтов


use App\Middlewares\DefaultMiddleware;
use App\Bootstrap\Services\Auth\RegType;

use Modules\Catalog\Controllers\{
    HomeController,
	AdminController,
	AddItemController,
	EditItemController,
	DetailedController,
	DirController,
	ReplyController,
	ImgController,
	UserAreaController,
};

Route::get('/web')->module('catalog', HomeController::class)->name('web');
Route::get('/web/dir/{sort}/{slug}')->module('catalog', DirController::class)->name('category');
Route::get('/web/website/{id}/{slug?}')->module('catalog', DetailedController::class)->where(['id' => '[0-9]+', 'slug' => '[a-z0-9-.]+'])->name('website');
Route::post('/search/web/url')->module('catalog', AddItemController::class, 'searchUrl');

// Формы добавления контента
Route::get('/add/item/{id?}')->module('catalog', AddItemController::class)->where(['id' => '[0-9]+'])->name('item.form.add');
Route::get('/add/category')->module('catalog', AddItemController::class)->name('category.form.add');
// Формы изменение контента
Route::get('/edit/item/{id}')->module('catalog', EditItemController::class)->where(['id' => '[0-9]+'])->name('item.form.edit');

Route::toGroup()->protect();
	// Отправка 
	// добавление контента
	Route::post('/add/reply')->module('catalog', ReplyController::class, 'add')->name('add.reply');
	Route::post('/add/item')->module('catalog', AddItemController::class, 'add')->name('add.item');
	
	// Отправка 
	// изменение контента
	Route::post('/edit/item')->module('catalog', EditItemController::class, 'edit')->name('edit.item');
	Route::post('/edit/reply')->module('catalog', ReplyController::class, 'edit')->name('edit.reply');
	
	// Открытие комментария
	Route::post('/activatingform/addreply')->controller(ReplyController::class, 'addForma');
	Route::post('/activatingform/editreply')->controller(ReplyController::class);
Route::endGroup(); 

Route::toGroup()->middleware(DefaultMiddleware::class, data: [RegType::REGISTERED_ADMIN, '=']);
    Route::get('/web/deleted')->module('catalog', AdminController::class, 'deleted')->name('web.deleted');
	Route::get('/web/audits')->module('catalog', AdminController::class, 'audits')->name('web.audits');
	Route::get('/web/comments')->module('catalog', AdminController::class, 'comments')->name('web.comments');
	Route::get('/web/status/{code?}')->module('catalog', AdminController::class, 'status')->where(['code' => '[0-9]+'])->name('web.status');
Route::endGroup();

Route::toGroup()->middleware(DefaultMiddleware::class, data: [RegType::USER_FIRST_LEVEL, '>=']);
	Route::post('/web/favicon/add')->module('catalog', ImgController::class, 'favicon');
	Route::post('/web/screenshot/add')->module('catalog', ImgController::class, 'screenshot');
	Route::post('/web/status/update')->module('catalog', AdminController::class, 'updateStatus');
	Route::get('/web/bookmarks')->module('catalog', UserAreaController::class, 'bookmarks')->name('web.bookmarks');
	Route::get('/web/my')->module('catalog', UserAreaController::class)->name('web.user.sites');
Route::endGroup();

Route::get('/web/{grouping}/dir/{sort}/{slug}')->module('catalog', DirController::class)->name('grouping.category');