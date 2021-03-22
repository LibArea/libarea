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

// Админка
Route::get('/admin')->controller('AdminController');

// Посты и главная страница
Route::get('/all')->controller('PostController@allPost');
Route::get('/top')->controller('PostController@topPost');
Route::get('/posts/{slug}/')->controller('PostController@view')->where(['slug' => '[A-Za-z0-9-]+']);

// Правила
Route::get('/info')->controller('InfoController');
Route::get('/info/stats')->controller('InfoController@stats');
Route::get('/info/rules')->controller('InfoController@rules');
Route::get('/info/about')->controller('InfoController@about');
Route::get('/info/privacy')->controller('InfoController@privacy');
Route::get('/info/trust-level')->controller('InfoController@trustlevel');

// Участник
Route::get('/users')->controller('UserController');
Route::get('/u/{login}')->controller('UserController@profile')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/users/setting')->controller('UserController@settingPage');
Route::get('/users/setting/avatar')->controller('UserController@settingPageAvatar');
Route::get('/users/setting/security')->controller('UserController@settingPageSecurity');
Route::type('post')->protect()->get('/users/setting/edit')->controller('UserController@settingEdit');
Route::type('post')->protect()->get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
Route::type('post')->protect()->get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');

// Избранное участника
Route::get('/favorite/{login}')->controller('UserController@userFavorite')->where(['login' => '[A-Za-z0-9]+']);

// Регистрация и авторизация
Route::get('/register')->controller('AuthController@registerPage');
Route::get('/recover')->controller('AuthController@recoverPage');
Route::type('post')->protect()->get('/register/add')->controller('AuthController@registerHandler');
Route::type('post')->protect()->get('/login')->controller('AuthController@loginHandler');
Route::type('get')->get('/login')->controller('AuthController@loginPage');
Route::get('/logout')->controller('AuthController@logout');
 
// Страница комментариев участника
Route::get('/threads/{login}')->controller('CommentController@userComments')->where(['login' => '[A-Za-z0-9]+']);
// Страница постов участника
Route::get('/newest/{login}')->controller('PostController@userPosts')->where(['login' => '[A-Za-z0-9]+']);

// Все комментарии
Route::get('/comments')->controller('CommentController');

// Пространства
Route::get('/space')->controller('SpaceController');
Route::get('/s/{space}')->controller('SpaceController@spacePosts')->where(['space' => '[A-Za-z0-9]+']);

// Подписываемся, отписываемся на тег
Route::type('post')->get('/space/hide/{id}')->controller('SpaceController@hide')->where(['id' => '[0-9]+']);

// Голосуем за комментарии
Route::type('post')->get('/votes/{id}')->controller('VotesCommController@votes')->where(['id' => '[0-9]+']);
 
// Добавляем комментарии 
Route::type('post')->protect()->get('/comment/add')->controller('CommentController@create');
// Вызов формы комментария
Route::type('post')->get('/comments/addform')->controller('CommentController@addform');
// Страница добавление поста
Route::get('/post/add')->controller('PostController@addPost');
// Само добавление поста
Route::type('post')->protect()->get('/post/create')->controller('PostController@createPost');
// Редактирование поста
Route::get('/post/edit/{id}')->controller('PostController@editPost');
Route::type('post')->protect()->get('/post/editpost/{id}')->controller('PostController@editPostRecording');

// Помещаем свой пост в профиль
Route::type('post')->get('/post/addpostprof/{id}')->controller('PostController@addPostProf')->where(['id' => '[0-9]+']);

// Личные сообщения 
Route::get('/messages')->controller('MessagesController');  
Route::type('post')->protect()->get('/messages/send')->controller('MessagesController@send');
Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
Route::get('/u/{login}/messages')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

// Уведомления 
Route::get('/notifications')->controller('NotificationsController'); 

// Поиск
Route::get('/search')->controller('SearchController');

// Пагинация и главная страница
Route::get('/{page?}')->protect()->controller('PostController')->where(['page' => '[0-9]+']);