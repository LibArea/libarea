<?php

/*
 * Main file for creating a routing map.
 * The routes change when the files in this folder are changed. If there is a time difference between the servers,
 * you must execute "php console -cc" or delete the cached "routes.txt" file after making the changes.
 *
 * Основной файл для создания карты маршрутизации.
 * Маршруты перерасчитываются при изменении файлов в этой папке. Если есть разница во времени между серверами, необходимо выполнить
 * «php console --clear-cache» или удалить кешированный файл «routes.txt» после внесения изменений.
 */

Route::before('Authorization@noAuth')->getGroup();
    Route::get('/admin')->controller('AdminController');
    Route::get('/admin/comments')->controller('AdminController@Comments'); 
    Route::get('/admin/invitations')->controller('AdminController@Invitations');
    Route::type('post')->get('/admin/comment/recover')->controller('AdminController@recoverComment');
    
    Route::type('post')->get('/admin/ban/{id}')->controller('AdminController@banUser')->where(['id' => '[0-9]+']);

	Route::get('/post/add')->controller('PostController@addPost');
	Route::type('post')->protect()->get('/post/create')->controller('PostController@createPost');
	Route::get('/post/edit/{id}')->controller('PostController@editPost');
	Route::type('post')->protect()->get('/post/editpost/{id}')->controller('PostController@editPostRecording');
	Route::type('post')->get('/post/del')->controller('PostController@deletePost');
    Route::type('post')->get('/post/grabtitle')->controller('PostController@grabTitle');

    Route::get('/users/invitation')->controller('UserController@invitationPage');
	Route::type('post')->protect()->get('/invitation/create')->controller('UserController@invitationCreate');

	Route::get('/users/setting')->controller('UserController@settingPage');
	Route::get('/users/setting/avatar')->controller('UserController@settingPageAvatar');
	Route::get('/users/setting/security')->controller('UserController@settingPageSecurity');
	Route::type('post')->protect()->get('/users/setting/edit')->controller('UserController@settingEdit');
	Route::type('post')->protect()->get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
	Route::type('post')->protect()->get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');

	Route::get('/logout')->controller('AuthController@logout');

	// Добавление комментария / удаление 
    Route::type('post')->get('/comments/editform')->controller('CommentController@editform');
    Route::type('post')->protect()->get('/comment/edit')->controller('CommentController@editComment');
	Route::type('post')->protect()->get('/comment/add')->controller('CommentController@create');
	Route::type('post')->get('/comment/del')->controller('CommentController@deletComment');

	// Помещаем свой пост в профиль
	Route::type('post')->get('/post/addpostprof')->controller('PostController@addPostProf');
	// В закладки
	Route::type('post')->get('/post/addfavorite')->controller('PostController@addPostFavorite');

	// Личные сообщения 
	Route::get('/messages')->controller('MessagesController');  
	Route::type('post')->protect()->get('/messages/send')->controller('MessagesController@send');
	Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
	Route::get('/u/{login}/messages')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

	// Уведомления 
	Route::get('/notifications')->controller('NotificationsController');

	// Подписываемся, отписываемся / изменяем пространство
	Route::type('post')->get('/space/hide')->controller('SpaceController@hide');
    Route::get('/space/{slug}/edit')->controller('SpaceController@spaceForma')->where(['slug' => '[A-Za-z0-9]+']);  
    Route::type('post')->protect()->get('/space/editspace')->controller('SpaceController@spaceEdit');
 
	// Голосуем
	Route::type('post')->get('/votes/comm')->controller('VotesCommController@votes');
    Route::type('post')->get('/votes/post')->controller('VotesPostController@votes');
Route::endGroup();

Route::before('Authorization@yesAuth')->getGroup();
	// Регистрация и авторизация (инвайты)
    Route::get('/invite')->controller('UserController@invitePage');
    Route::type('post')->protect()->get('/invite')->controller('UserController@inviteHandler');
	Route::get('/register')->controller('AuthController@registerPage');
    Route::type('get')->get('/register/invite/{code}')->controller('AuthController@registerPageInvite')->where(['code' => '[a-z0-9-]+']);
    Route::type('get')->get('/recover')->controller('AuthController@recoverPage');  
    Route::type('post')->protect()->get('/recover/send')->controller('AuthController@sendRecover'); 
    Route::type('get')->get('/recover/remind/{code}')->controller('AuthController@RemindPage')->where(['code' => '[A-Za-z0-9-]+']);
    Route::type('post')->protect()->get('/recover/send/pass')->controller('AuthController@RemindPageNew'); 
	Route::type('post')->protect()->get('/register/add')->controller('AuthController@registerHandler');
	Route::type('post')->protect()->get('/login')->controller('AuthController@loginHandler');
	Route::type('get')->get('/login')->controller('AuthController@loginPage'); 
Route::endGroup();

// Посты и главная страница
Route::get('/top')->controller('PostController@topPost');
Route::get('/posts/{slug}')->controller('PostController@view')->where(['slug' => '[A-Za-z0-9-]+']);

// Правила
Route::get('/info')->controller('InfoController');
Route::get('/info/stats')->controller('InfoController@stats');
Route::get('/info/rules')->controller('InfoController@rules');
Route::get('/info/about')->controller('InfoController@about');
Route::get('/info/privacy')->controller('InfoController@privacy');
Route::get('/info/trust-level')->controller('InfoController@trustlevel');
Route::get('/info/restriction')->controller('InfoController@restriction');
Route::get('/info/markdown')->controller('InfoController@markdown');

// Покажем пост в ленте
Route::type('post')->get('/post/shown')->controller('PostController@shownPost');

// Участники
Route::get('/users')->controller('UserController');
Route::get('/u/{login}')->controller('UserController@profile')->where(['login' => '[A-Za-z0-9]+']);

// Избранное участника
Route::get('/favorite/{login}')->controller('UserController@userFavorite')->where(['login' => '[A-Za-z0-9]+']);

// Страница комментариев участника
Route::get('/threads/{login}')->controller('CommentController@userComments')->where(['login' => '[A-Za-z0-9]+']);
// Страница постов участника
Route::get('/newest/{login}')->controller('PostController@userPosts')->where(['login' => '[A-Za-z0-9]+']);

// Все комментарии
Route::get('/comments')->controller('CommentController');

// Пространства
Route::get('/space')->controller('SpaceController');
Route::get('/s/{slug}')->controller('SpaceController@spacePosts')->where(['slug' => '[A-Za-z0-9]+']);

// Вызов формы комментария
Route::type('post')->get('/comments/addform')->controller('CommentController@addform');

// Поиск
Route::type(['get','post'])->get('/search')->controller('SearchController');

// Пагинация и главная страница
Route::get('/{page?}')->protect()->controller('PostController')->where(['page' => '[0-9]+']);