<?php

/*
 * Main file for creating a routing map.
 * The routes change when the files in this folder are changed. If there is a time difference between the servers,
 * you must execute "php console -routes-cc" or delete the cached "routes.txt" file after making the changes.
 *
 * Основной файл для создания карты маршрутизации.
 * Маршруты перерасчитываются при изменении файлов в этой папке. Если есть разница во времени между серверами, необходимо выполнить
 * «php console -routes-cc» или удалить кешированный файл «routes.txt» после внесения изменений.
 */

Route::before('Authorization@noAuth')->getGroup();
    Route::get('/admin/{page?}')->controller('AdminController')->where(['page' => '[0-9]+']);
    Route::get('/admin/user/{id}/edit')->controller('AdminController@userEditPage')->where(['id' => '[0-9]+']);
    Route::type('post')->protect()->get('/admin/user/edit/{id}')->controller('AdminController@userEdit')->where(['id' => '[0-9]+']);
    Route::get('/admin/spaces')->controller('AdminController@spaces'); 
    Route::get('/admin/space/add')->controller('AdminController@addSpacePage');
    Route::get('/admin/logip/{ip}')->controller('AdminController@logsIp')->where(['ip' => '[0-9].+']);
    Route::type('post')->protect()->get('/admin/addspaceadmin')->controller('AdminController@spaceAdd');
    Route::get('/admin/comments')->controller('AdminController@comments'); 
    Route::get('/admin/invitations')->controller('AdminController@invitations');
    
    Route::get('/admin/badges')->controller('AdminController@badges');
    Route::get('/admin/badge/add')->controller('AdminController@addBadgePage');
    Route::type('post')->protect()->get('/admin/badge/user/addform')->controller('AdminController@addBadgeUser');
    Route::get('/admin/badge/user/add')->controller('AdminController@addBadgeUserPage');
    Route::get('/admin/badge/user/add/{id}')->controller('AdminController@addBadgeUserPage')->where(['id' => '[0-9]+']);
    Route::get('/admin/badge/{id}/edit')->controller('AdminController@badgeEditPage')->where(['id' => '[0-9]+']);
    Route::type('post')->protect()->get('/admin/badge/edit/{id}')->controller('AdminController@badgeEdit')->where(['id' => '[0-9]+']);
    Route::type('post')->protect()->get('/admin/badge/add')->controller('AdminController@badgeAdd');
    
    Route::get('/post/img/{id}/remove')->controller('PostController@postImgRemove')->where(['id' => '[0-9]+']);
    Route::type('post')->get('/admin/comment/recover')->controller('AdminController@recoverComment');
    Route::type('post')->get('/admin/space/ban')->controller('AdminController@delSpace');
    Route::type('post')->get('/admin/ban')->controller('AdminController@banUser');

	Route::get('/post/add')->controller('PostController@addPost');
    Route::get('/post/add/space/{space_id}')->controller('PostController@addPost')->where(['space_id' => '[0-9]+']);
	Route::type('post')->protect()->get('/post/create')->controller('PostController@createPost');
	Route::get('/post/edit/{id}')->controller('PostController@editPost');
	Route::type('post')->protect()->get('/post/editpost/{id}')->controller('PostController@editPostRecording');
	Route::type('post')->get('/post/del')->controller('PostController@deletePost');
    Route::type('post')->get('/post/grabtitle')->controller('PostController@grabTitle');

    // Инвайты
    Route::get('/u/{login}/invitation')->controller('UserController@invitationPage')->where(['login' => '[A-Za-z0-9]+']); 
	Route::type('post')->protect()->get('/invitation/create')->controller('UserController@invitationCreate');

	Route::get('/u/{login}/setting')->controller('UserController@settingPage')->where(['login' => '[A-Za-z0-9]+']); 
	Route::get('/u/{login}/setting/avatar')->controller('UserController@settingPageAvatar')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/delete/cover')->controller('UserController@userCoverRemove')->where(['login' => '[A-Za-z0-9]+']); 
	Route::get('/u/{login}/setting/security')->controller('UserController@settingPageSecurity')->where(['login' => '[A-Za-z0-9]+']); 
    
	Route::type('post')->protect()->get('/users/setting/edit')->controller('UserController@settingEdit');
	Route::type('post')->protect()->get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
	Route::type('post')->protect()->get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');

	Route::get('/logout')->controller('AuthController@logout');

	Route::type('post')->protect()->get('/flow/add')->controller('FlowController@chatAdd');
    Route::type('post')->get('/flow/del')->controller('FlowController@deleteFlow');
    
	// Добавление комментария / удаление 
    Route::type('post')->get('/comment/editform')->controller('CommentController@editFormComment');
    Route::type('post')->protect()->get('/comment/edit')->controller('CommentController@editComment');
	Route::type('post')->protect()->get('/comment/add')->controller('CommentController@createComment');
    Route::type('post')->get('/comment/del')->controller('CommentController@deletComment');

	// Добавление ответов / удаление / изменение
    Route::get('/post/{post_id}/answ/{answ_id}')->controller('AnswerController@editFormAnswer')->where(['post_id' => '[0-9]+', 'answ_id' => '[0-9]+']);
    Route::type('post')->protect()->get('/answer/edit')->controller('AnswerController@editAnswer');
	Route::type('post')->protect()->get('/answer/add')->controller('AnswerController@createAnswer');
    Route::type('post')->get('/answer/del')->controller('AnswerController@deletAnswer');
    Route::type('post')->get('/answer/addfavorite')->controller('AnswerController@addAnswerFavorite');

	// Помещаем свой пост в профиль
	Route::type('post')->get('/post/addpostprof')->controller('PostController@addPostProf');
	// В закладки
	Route::type('post')->get('/post/addfavorite')->controller('PostController@addPostFavorite');

	// Личные сообщения 
	Route::get('/u/{login}/messages')->controller('MessagesController')->where(['login' => '[A-Za-z0-9]+']);   
	Route::type('post')->protect()->get('/messages/send')->controller('MessagesController@send');
	Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
	Route::get('/u/{login}/mess')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

	// Уведомления 
	Route::get('/u/{login}/notifications')->controller('NotificationsController')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/notifications/read/{id}')->controller('NotificationsController@notifRead')->where(['id' => '[0-9]+']);  
    Route::get('/notifications/delete')->controller('NotificationsController@notifRemove');  
    
    // Избранное и черновики
    Route::get('/u/{login}/favorite')->controller('UserController@userFavorites')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/drafts')->controller('UserController@userDrafts')->where(['login' => '[A-Za-z0-9]+']);

	// Подписываемся, отписываемся / изменяем пространство
	Route::type('post')->get('/space/hide')->controller('SpaceController@hide');
    Route::get('/space/{slug}/edit')->controller('SpaceController@spaceForma')->where(['slug' => '[A-Za-z0-9_]+']); 
    Route::get('/space/{slug}/edit/logo')->controller('SpaceController@spaceFormaLogo')->where(['slug' => '[A-Za-z0-9_]+']);  
    Route::get('/space/{slug}/tags')->controller('SpaceController@spaceTagsInfo')->where(['slug' => '[A-Za-z0-9_]+']); 
    Route::get('/space/{slug}/tags/add')->controller('SpaceController@spaceTagsAddPage')->where(['slug' => '[A-Za-z0-9_]+']);
    Route::type('post')->protect()->get('/space/editspace')->controller('SpaceController@spaceEdit');
    Route::type('post')->protect()->get('/space/editspace/logo')->controller('SpaceController@spaceEditLogo');
    Route::get('/space/{slug}/delete/cover')->controller('SpaceController@spaceCoverRemove')->where(['slug' => '[A-Za-z0-9_]+']);
    Route::get('/space/add')->controller('SpaceController@addSpacePage');
    Route::type('post')->protect()->get('/space/addspace')->controller('SpaceController@spaceAdd');
    Route::get('/space/my')->controller('SpaceController@spaseUser');
 
    // Работа с метками (тегами)
    Route::get('/s/{slug}/{tags?}/edit')->controller('SpaceController@editTagSpacePage')->where(['slug' => '[A-Za-z0-9_]+',  'tags' => '[0-9]+']);
    Route::type('post')->protect()->get('/space/tag/edit')->controller('SpaceController@editTagSpace');
    Route::type('post')->protect()->get('/space/tag/add')->controller('SpaceController@addTagSpace');
    
    // Для авторизированных по умолчанию включается режим ленты (feed) и нет возможности посмотреть все посты 
    // из различных пространств. Кроме черновиков, удаленных и ограниченных TL.
    Route::get('/all/{page?}')->controller('PostController', ['all'])->where(['page' => '[0-9]+']);
    
	// Голосуем
	Route::type('post')->get('/votes/comm')->controller('VotesCommController@votes');
    Route::type('post')->get('/votes/post')->controller('VotesPostController@votes');
    Route::type('post')->get('/votes/answ')->controller('VotesAnswController@votes');
Route::endGroup();

Route::before('Authorization@yesAuth')->getGroup();
	// Регистрация и авторизация (инвайты)
    Route::get('/invite')->controller('UserController@invitePage');
	Route::get('/register')->controller('AuthController@registerPage');
    Route::type('get')->get('/register/invite/{code}')->controller('AuthController@registerPageInvite')->where(['code' => '[a-z0-9-]+']);
    Route::type('get')->get('/recover')->controller('AuthController@recoverPage');  
    Route::type('post')->protect()->get('/recover/send')->controller('AuthController@sendRecover'); 
    Route::type('get')->get('/recover/remind/{code}')->controller('AuthController@RemindPage')->where(['code' => '[A-Za-z0-9-]+']);
    Route::type('get')->get('/email/avtivate/{code}')->controller('AuthController@AvtivateEmailPage')->where(['code' => '[A-Za-z0-9-]+']);
    Route::type('post')->protect()->get('/recover/send/pass')->controller('AuthController@RemindPageNew'); 
	Route::type('post')->protect()->get('/register/add')->controller('AuthController@registerHandler');
	Route::type('post')->protect()->get('/login')->controller('AuthController@loginHandler');
	Route::type('get')->get('/login')->controller('AuthController@loginPage'); 
Route::endGroup();

// Пост в ленте и полный пост
Route::type('post')->get('/post/shown')->controller('PostController@shownPost');
Route::get('/post/{id}')->controller('PostController@viewPost')->where(['id' => '[0-9-]+']);
Route::get('/post/{id}/{slug}')->controller('PostController@viewPost')->where(['id' => '[0-9-]+', 'slug' => '[A-Za-z0-9-_]+']);

// Информация
Route::get('/info')->controller('InfoController');
Route::get('/info/privacy')->controller('InfoController@privacy');
Route::get('/info/restriction')->controller('InfoController@restriction');

// Участники, авторизация, посты и комментарии, закладки
Route::get('/users')->controller('UserController');
Route::get('/u/{login}')->controller('UserController@profile')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/posts')->controller('PostController@userPosts')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/answers')->controller('AnswerController@userAnswers')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/comments')->controller('CommentController@userComments')->where(['login' => '[A-Za-z0-9]+']);

// Поток
Route::get('/flow')->controller('FlowController');
Route::get('/flow/content')->controller('FlowController@contentChat');

// Все комментарии и ответы
Route::get('/comments')->controller('CommentController');
Route::get('/answers')->controller('AnswerController');

// Исследовать
Route::get('/explore')->controller('ExploreController');

// Пространства
Route::get('/space')->controller('SpaceController');
Route::get('/s/{slug}/{tags?}')->controller('SpaceController@spacePosts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+',  'tags' => '[0-9]+']);
Route::get('/s/{slug}/top/{tags?}')->controller('SpaceController@spacePosts', ['top'])->where(['slug' => '[A-Za-z0-9_]+',  'tags' => '[0-9]+']);

// Вызов формы комментария
Route::type('post')->get('/comments/addform')->controller('CommentController@addFormComm');

// Поиск
Route::type(['get','post'])->get('/search')->controller('SearchController');
Route::get('/domain/{domain}')->controller('SearchController@domain')->where(['domain' => '[A-Za-z0-9-.]+']);

Route::type(['get','post'])->get('/search/users')->controller('PostController@userSelect');
Route::type(['get','post'])->get('/search/posts')->controller('PostController@postsSelect');

// Пагинация и главная (feed) страница, top, all...
Route::get('/{page?}')->controller('PostController', ['feed'])->where(['page' => '[0-9]+']);
Route::get('/top/{page?}')->controller('PostController', ['top'])->where(['page' => '[0-9]+']);
Route::get('/comments/{page?}')->controller('CommentController')->where(['page' => '[0-9]+']);

// Карта сайта и Турбо страницы (пространств)
Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/space/{id}')->controller('RssController@turboFeed')->where(['id' => '[0-9]+']);