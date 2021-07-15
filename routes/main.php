<?php
// https://phphleb.ru/ru/v1/types/

Route::before('Authorization@admin')->getGroup();
    Route::getType('post');
        Route::get('/admin/space/ban')->controller('AdminController@delSpace');
        Route::get('/admin/ban')->controller('AdminController@banUser');
        Route::get('/admin/favicon/add')->controller('LinkController@favicon');
        Route::get('/admin/word/del')->controller('AdminController@deleteWord');
        Route::get('/admin/audit/status')->controller('AdminController@status');
        
        Route::getProtect();
            Route::get('/admin/user/edit/{id}')->controller('AdminController@userEdit')->where(['id' => '[0-9]+']);
            Route::get('/admin/domain/edit/{id}')->controller('AdminController@domainEdit')->where(['id' => '[0-9]+']);
            Route::get('/admin/badge/user/addform')->controller('AdminController@addBadgeUser');
            Route::get('/admin/badge/edit/{id}')->controller('AdminController@badgeEdit')->where(['id' => '[0-9]+']);
            Route::get('/admin/badge/add')->controller('AdminController@badgeAdd');
            Route::get('/admin/word/add')->controller('AdminController@createWord');
            Route::get('/admin/addspaceadmin')->controller('AdminController@spaceAdd');
            Route::get('/admin/addtopicadmin')->controller('TopicController@topicAdd');
            Route::get('/admin/topic/edit/{id}')->controller('TopicController@topicEdit')->where(['id' => '[0-9]+']);
        Route::endProtect();
    Route::endType();
    
    Route::get('/admin')->controller('AdminController', ['all']);
    Route::get('/admin/all/page/{page?}')->controller('AdminController', ['all'])->where(['page' => '[0-9]+']);
    Route::get('/admin/ban')->controller('AdminController', ['ban']);
    Route::get('/admin/ban/page/{page?}')->controller('AdminController', ['ban'])->where(['page' => '[0-9]+']);
    Route::get('/admin/user/{id}/edit')->controller('AdminController@userEditPage')->where(['id' => '[0-9]+']);

    Route::get('/admin/words')->controller('AdminController@words');
    Route::get('/admin/wordadd')->controller('AdminController@wordsAddForm');
    
    Route::get('/admin/audit')->controller('AdminController@audit', ['all']);
    Route::get('/admin/audit/approved')->controller('AdminController@audit', ['approved']);

    Route::get('/admin/spaces')->controller('AdminController@spaces');
    Route::get('/admin/spaces/page/{page?}')->controller('AdminController@spaces')->where(['page' => '[0-9]+']);
    Route::get('/admin/space/add')->controller('AdminController@addSpacePage');
    Route::get('/admin/logip/{ip}')->controller('AdminController@logsIp')->where(['ip' => '[0-9].+']);
    
    Route::get('/admin/comments')->controller('AdminController@comments');
    Route::get('/admin/comments/page/{page?}')->controller('AdminController@comments')->where(['page' => '[0-9]+']);
    Route::get('/admin/answers')->controller('AdminController@answers');
    Route::get('/admin/answers/page/{page?}')->controller('AdminController@answers')->where(['page' => '[0-9]+']);
    
    Route::get('/admin/invitations')->controller('AdminController@invitations');
    
    Route::get('/admin/domain/{id}/edit')->controller('AdminController@editDomain')->where(['id' => '[0-9]+']);
    Route::get('/admin/domains')->controller('AdminController@domains');
    Route::get('/admin/domains/page/{page?}')->controller('AdminController@domains')->where(['page' => '[0-9]+']);
        
    Route::get('/admin/badges')->controller('AdminController@badges');
    Route::get('/admin/badge/add')->controller('AdminController@addBadgeForm');
 
    Route::get('/admin/topics')->controller('AdminController@topics');
    Route::get('/admin/topics/page/{page?}')->controller('AdminController@topics')->where(['page' => '[0-9]+']);
    Route::get('/admin/topic/add')->controller('TopicController@addTopicForm');
    Route::get('/admin/topic/{id}/edit')->controller('TopicController@editTopicForm')->where(['id' => '[0-9]+']);
 
    Route::get('/admin/badge/user/add')->controller('AdminController@addBadgeUserPage');
    Route::get('/admin/badge/user/add/{id}')->controller('AdminController@addBadgeUserPage')->where(['id' => '[0-9]+']);
    Route::get('/admin/badge/{id}/edit')->controller('AdminController@editBadgeForm')->where(['id' => '[0-9]+']);
Route::endGroup();

Route::before('Authorization@noAuth')->getGroup();
    Route::getType('post');
        Route::get('/status/action')->controller('PublishController@deletingAndRestoring');

        Route::get('/votes/post')->controller('VotesController', ['post']); 
        Route::get('/votes/answer')->controller('VotesController', ['answer']); 
        Route::get('/votes/comment')->controller('VotesController', ['comment']);
        Route::get('/votes/link')->controller('VotesController', ['link']);

        Route::get('/post/grabtitle')->controller('PostController@grabMeta');
        Route::get('/comment/editform')->controller('CommentController@editCommentForm');
 
        Route::get('/post/addpostprof')->controller('PostController@addPostProfile');
        
        Route::get('/favorite/post')->controller('FavoriteController', ['post']);
        Route::get('/favorite/answer')->controller('FavoriteController', ['answer']);
        
        Route::get('/search/users')->controller('PostController@select', ['user']);
        Route::get('/search/posts')->controller('PostController@select', ['posts']);
        Route::get('/search/topics')->controller('PostController@select', ['topics']);
        Route::get('/search/topics/main')->controller('PostController@select', ['main']);
 
        Route::get('/space/focus')->controller('SpaceController@focus');
        Route::get('/topic/focus')->controller('TopicController@focus');
        
        Route::getProtect(); // Начало защиты
            Route::get('/space/editspace')->controller('SpaceController@edit');
            Route::get('/space/editspace/logo')->controller('SpaceController@logoEdit');
            Route::get('/space/addspace')->controller('SpaceController@add');
            Route::get('/users/setting/edit')->controller('UserController@settingEdit');
            Route::get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
            Route::get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');
            Route::get('/post/create')->controller('PublishController@createPost');
            Route::get('/comment/add')->controller('PublishController@createComment');
            Route::get('/answer/add')->controller('PublishController@createAnswer');
            Route::get('/post/editpost/{id}')->controller('PostController@edit');
            Route::get('/comment/edit')->controller('CommentController@editComment');
            Route::get('/answer/edit')->controller('AnswerController@editAnswer');
            Route::get('/invitation/create')->controller('UserController@invitationCreate');
            Route::get('/users/setting/edit')->controller('UserController@settingEdit');
            Route::get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
            Route::get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');
            Route::get('/messages/send')->controller('MessagesController@send');
        Route::endProtect(); // Завершение защиты
    Route::endType();  // Завершение getType('post')

    Route::get('/post/img/{id}/remove')->controller('PostController@imgPostRemove')->where(['id' => '[0-9]+']);

    Route::get('/post/add')->controller('PublishController@addPostForm');
    Route::get('/post/add/space/{space_id}')->controller('PublishController@addPostForm')->where(['space_id' => '[0-9]+']);
 
    Route::get('/post/edit/{id}')->controller('PostController@editPostForm');
    
    Route::get('/u/{login}/invitation')->controller('UserController@invitationPage')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/u/{login}/setting')->controller('UserController@settingForm')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/u/{login}/setting/avatar')->controller('UserController@settingAvatarForm')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/setting/security')->controller('UserController@settingSecurityForm')->where(['login' => '[A-Za-z0-9]+']); 
    
    Route::get('/u/{login}/delete/cover')->controller('UserController@userCoverRemove')->where(['login' => '[A-Za-z0-9]+']); 

    Route::get('/logout')->controller('AuthController@logout');

	// Добавление ответов / удаление / изменение
    Route::get('/post/{post_id}/answ/{answer_id}')->controller('AnswerController@editAnswerPage')->where(['post_id' => '[0-9]+', 'answer_id' => '[0-9]+']);

	// Личные сообщения 
    Route::get('/u/{login}/messages')->controller('MessagesController')->where(['login' => '[A-Za-z0-9]+']);   

    Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
    Route::get('/u/{login}/mess')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

	// Уведомления 
	Route::get('/u/{login}/notifications')->controller('NotificationsController')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/notifications/read/{id}')->controller('NotificationsController@read')->where(['id' => '[0-9]+']);  
    Route::get('/notifications/delete')->controller('NotificationsController@remove');  
    
    Route::get('/admin/update/count')->controller('AdminController@updateQuantity'); 
    
    // Избранное и черновики
    Route::get('/u/{login}/favorite')->controller('UserController@userFavorites')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/drafts')->controller('UserController@userDrafts')->where(['login' => '[A-Za-z0-9]+']);

	// Подписываемся, отписываемся / изменяем пространство
    Route::get('/space/{slug}/edit')->controller('SpaceController@editForm')->where(['slug' => '[A-Za-z0-9_]+']); 
    Route::get('/space/{slug}/edit/logo')->controller('SpaceController@logoForm')->where(['slug' => '[A-Za-z0-9_]+']);  
 
    Route::get('/space/{slug}/delete/cover')->controller('SpaceController@coverRemove')->where(['slug' => '[A-Za-z0-9_]+']);
    Route::get('/space/add')->controller('SpaceController@addForm');
    Route::get('/space/my')->controller('SpaceController@spaseUser');
    Route::get('/space/my/page/{page?}')->controller('SpaceController@spaseUser')->where(['page' => '[0-9]+']);
 
    Route::get('/all')->controller('HomeController', ['all']);
    Route::get('/all/page/{page?}')->controller('HomeController', ['all'])->where(['page' => '[0-9]+']);
Route::endGroup();

Route::before('Authorization@yesAuth')->getGroup();
    Route::getType('post');
        Route::getProtect();
            Route::get('/recover/send')->controller('AuthController@sendRecover'); 
            Route::get('/recover/send/pass')->controller('AuthController@remindNew'); 
            Route::get('/register/add')->controller('AuthController@register');
            Route::get('/login')->controller('AuthController@login');
        Route::endProtect();
    Route::endType();

    Route::get('/invite')->controller('UserController@inviteForm');
	Route::get('/register')->controller('AuthController@registerForm');
    
    Route::getType('get');
        Route::get('/register/invite/{code}')->controller('AuthController@registerInviteForm')->where(['code' => '[a-z0-9-]+']);
        Route::get('/recover')->controller('AuthController@recoverForm');  
        Route::get('/recover/remind/{code}')->controller('AuthController@RemindForm')->where(['code' => '[A-Za-z0-9-]+']);
        Route::get('/email/avtivate/{code}')->controller('AuthController@AvtivateEmail')->where(['code' => '[A-Za-z0-9-]+']);
        Route::get('/login')->controller('AuthController@loginForm'); 
    Route::endType();
Route::endGroup();

Route::getType('post');
    // Пост в ленте и полный пост
    Route::get('/post/shown')->controller('PostController@shownPost');
    // Вызов формы комментария и поиск
    Route::get('/comments/addform')->controller('CommentController@addForm');
Route::endType();

// Другие страницы без авторизии
Route::get('/post/{id}')->controller('PostController')->where(['id' => '[0-9-]+']);
Route::get('/post/{id}/{slug}')->controller('PostController')->where(['id' => '[0-9-]+', 'slug' => '[A-Za-z0-9-_]+']);

Route::get('/info')->controller('InfoController');
Route::get('/info/privacy')->controller('InfoController@privacy');
Route::get('/info/restriction')->controller('InfoController@restriction');

Route::get('/users')->controller('UserController');
Route::get('/users/page/{page?}')->controller('UserController')->where(['page' => '[0-9]+']);
Route::get('/u/{login}')->controller('UserController@profile')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/posts')->controller('PostController@userPosts')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/answers')->controller('AnswerController@userAnswers')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/comments')->controller('CommentController@userComments')->where(['login' => '[A-Za-z0-9]+']);

Route::get('/comments')->controller('CommentController');
Route::get('/comments/page/{page?}')->controller('CommentController')->where(['page' => '[0-9]+']);
Route::get('/answers')->controller('AnswerController');
Route::get('/answers/page/{page?}')->controller('AnswerController')->where(['page' => '[0-9]+']);

Route::get('/spaces')->controller('SpaceController');
Route::get('/spaces/page/{page?}')->controller('SpaceController')->where(['page' => '[0-9]+']);
Route::get('/s/{slug}')->controller('SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/page/{page?}')->controller('SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);
Route::get('/s/{slug}/top')->controller('SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/top/page/{page?}')->controller('SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);

Route::get('/moderations')->controller('PublishController@moderation');

Route::get('/topics')->controller('TopicController')->where(['page' => '[0-9]+']);
Route::get('/topics/page/{page?}')->controller('TopicController')->where(['page' => '[0-9]+']);
Route::get('/topic/{slug}')->controller('TopicController@topic')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/topic/{slug}/info')->controller('TopicController@info')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/topic/{slug}/page/{page?}')->controller('TopicController@topic')->where(['slug' => '[A-Za-z0-9-]+', 'page' => '[0-9]+']);

Route::get('/domains')->controller('LinkController');
Route::get('/domains/page/{page?}')->controller('LinkController')->where(['page' => '[0-9]+']);
Route::get('/domain/{domain}')->controller('LinkController@domain')->where(['domain' => '[A-Za-z0-9-.]+']);

Route::get('/')->controller('HomeController', ['feed']);
Route::get('/page/{page?}')->controller('HomeController', ['feed'])->where(['page' => '[0-9]+']);
Route::get('/top')->controller('HomeController', ['top']);
Route::get('/top/page/{page?}')->controller('HomeController', ['top'])->where(['page' => '[0-9]+']);

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/space/{id}')->controller('RssController@turboFeed')->where(['id' => '[0-9]+']);

Route::type(['get', 'post'])->get('/search')->controller('SearchController');