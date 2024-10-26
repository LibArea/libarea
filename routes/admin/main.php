<?php

use App\Middlewares\DefaultMiddleware;
use App\Bootstrap\Services\Auth\RegType;

use Modules\Admin\Controllers\{
    AdminController,
    UsersController,
    FacetsController,
    ToolsController,
    SettingController,
    LogsController,
    AuditsController,
    WordsController,
    BadgesController,
    InvitationsController,
    CssController,
    ConsoleController,
};

use App\Controllers\{
	Comment\CommentController,
	Comment\EditCommentController,
	HomeController,
};	

Route::toGroup()
	->prefix('/mod/admin/')
	->middleware(DefaultMiddleware::class, data: [RegType::REGISTERED_ADMIN, '=']);

	Route::get('/')->module('admin', AdminController::class)->name('admin');
	Route::post('/test/mail')->module('admin', ConsoleController::class, 'testMail')->name('admin.test.mail');
	Route::post('/user/ban')->module('admin', UsersController::class, 'banUser');
	Route::post('/word/ban')->module('admin', WordsController::class, 'deletes');
	Route::post('/audit/status')->module('admin', AuditsController::class, 'statusApproved');
	Route::post('/reports/saw')->module('admin', AuditsController::class, 'saw');
	Route::post('/topic/ban')->module('admin', FacetsController::class, 'deletes');
	Route::post('/badge/remove')->module('admin', BadgesController::class, 'remove');
	Route::post('/manual/update')->module('admin', ConsoleController::class);

	Route::toGroup()->protect();
		Route::post('/badge/user/create')->module('admin', BadgesController::class, 'rewarding')->name('admin.user.badge.create');
		Route::post('/badge/create')->module('admin', BadgesController::class, 'create')->name('admin.badge.create');
		Route::post('/badge/edit/{id}')->module('admin', BadgesController::class, 'edit')->where(['id' => '[0-9]+'])->name('admin.badge.edit');
		Route::post('/word/create')->module('admin', WordsController::class, 'create')->name('admin.word.create');
		Route::post('/user/edit/{id}')->module('admin', UsersController::class, 'edit')->where(['id' => '[0-9]+'])->name('admin.user.edit');
		Route::post('/setting/edit')->module('admin', SettingController::class, 'edit')->name('admin.setting.edit');
		Route::post('/users/search/go')->module('admin', UsersController::class, 'UserSearch')->where(['type' => '[a-zA-Z0-9]+'])->name('admin.user.search');
	Route::endGroup();

	Route::get('/users')->module('admin', UsersController::class, 'all')->name('admin.users');
	Route::get('/users/ban')->module('admin', UsersController::class, 'ban')->name('admin.users.ban');
	Route::get('/users/search')->module('admin', UsersController::class, 'search')->name('admin.users.search');
	
	Route::get('/facets')->module('admin', FacetsController::class)->name('admin.facets.all');
	Route::get('/tools')->module('admin', ToolsController::class)->name('admin.tools');

	// menu
	Route::get('/setting')->module('admin', SettingController::class)->name('admin.settings.general');
	Route::get('/setting/interface')->module('admin', SettingController::class)->name('admin.settings.interface');
	Route::get('/setting/advertising')->module('admin', SettingController::class)->name('admin.settings.advertising');

	Route::get('/edit/comment/transfer/{id}')->controller(EditCommentController::class, 'transfer')->where(['id' => '[0-9]+'])->name('admin.comment.transfer.form.edit'); 

	Route::get('/audits')->module('admin', AuditsController::class, 'all')->name('admin.audits');
	Route::get('/audits/approved')->module('admin', AuditsController::class, 'audits')->name('admin.audits.ban');
	Route::get('/report')->module('admin', AuditsController::class, 'report')->name('admin.reports');
	
	Route::get('/invitations')->module('admin', InvitationsController::class)->name('admin.invitations');
	Route::get('/logs/search')->module('admin', LogsController::class, 'logsSearch')->name('admin.logs.search');
	Route::get('/logs')->module('admin', LogsController::class)->name('admin.logs');
	Route::get('/words')->module('admin', WordsController::class)->name('admin.words');
	Route::get('/badges')->module('admin', BadgesController::class)->name('admin.badges');
	Route::get('/css')->module('admin', CssController::class)->name('admin.css');

	Route::get('/words/add')->module('admin', WordsController::class, 'add')->name('words.add');

	Route::get('/users/{id}/edit')->module('admin', UsersController::class, 'editForm')->where(['id' => '[0-9]+'])->name('admin.user.edit.form');
	Route::get('/users/{id}/history')->module('admin', UsersController::class, 'history')->where(['id' => '[0-9]+'])->name('admin.user.history');

	Route::get('/logip/{item}')->module('admin', UsersController::class, 'logip')->where(['item' => '[0-9].+'])->name('admin.logip');
	Route::get('/regip/{item}')->module('admin', UsersController::class, 'regip')->where(['item' => '[0-9].+'])->name('admin.regip');
	Route::get('/deviceid/{item}')->module('admin', UsersController::class, 'deviceid')->where(['item' => '[0-9].+'])->name('admin.device');

	Route::get('/badges/add')->module('admin', BadgesController::class, 'add')->name('admin.badges.add');
	Route::get('/badges/{id}/edit')->module('admin', BadgesController::class, 'editBadge')->where(['id' => '[0-9]+'])->name('admin.badges.edit');
	Route::get('/badges/user/add/{id}')->module('admin', BadgesController::class, 'addBadgeUser')->where(['id' => '[0-9]+'])->name('admin.badges.user.add');

	Route::get('/facets/{type}')->module('admin', FacetsController::class, 'type')->where(['type' => '[a-z]+'])->name('admin.facets.type');
	Route::get('/facets/ban/{type}')->module('admin', FacetsController::class, 'ban')->where(['type' => '[a-z]+'])->name('admin.facets.ban.type');

    // Deleted: posts in the feed, comments
    // Удаленные: посты в ленте, комментарии
	Route::get('/deleted')->controller(HomeController::class, 'deleted')->name('main.deleted');
	Route::get('comments/deleted')->controller(CommentController::class, 'deleted')->name('comments.deleted');
Route::endGroup();
