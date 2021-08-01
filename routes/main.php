<?php
// https://phphleb.ru/ru/v1/groups/

Route::before('Authorization@noAuth')->getGroup();
    Route::getType('post');
        Route::get('/backend/uploadimage')->controller('Post\EditPostController@uploadimage');
        Route::get('/status/action')->controller('ActionController@deletingAndRestoring');
        Route::get('/post/grabtitle')->controller('Post\AddPostController@grabMeta');
        Route::get('/comment/editform')->controller('Comment\EditCommentController@edit');
        Route::get('/post/addpostprof')->controller('Post\PostController@addPostProfile');
        Route::get('/favorite/post')->controller('FavoriteController', ['post']);
        Route::get('/favorite/answer')->controller('FavoriteController', ['answer']);
        Route::get('/space/focus')->controller('Space\SpaceController@focus');
        Route::get('/topic/focus')->controller('Topic\TopicController@focus');
        // @ users | posts | topics | mains 
        Route::get('/search/{type}')->controller('ActionController@select')->where(['type' => '[a-z]+']);
        // @ post | answer | comment | link
        Route::get('/votes/{type}')->controller('VotesController')->where(['type' => '[a-z]+']); 
            Route::getProtect(); // Начало защиты
                Route::get('/invitation/create')->controller('UserController@invitationCreate');
                Route::get('/messages/send')->controller('MessagesController@send');
                Route::get('/space/logo/edit')->controller('Space\EditSpaceController@logoEdit');
                Route::get('/users/setting/edit')->controller('UserController@settingEdit');
                Route::get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
                Route::get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');
                Route::get('/users/setting/edit')->controller('UserController@settingEdit');
                Route::get('/users/setting/avatar/edit')->controller('UserController@settingAvatarEdit');
                Route::get('/users/setting/security/edit')->controller('UserController@settingSecurityEdit');
                // post | comment | answer| topic | space
                Route::get('/{controller}/create')->controller('<controller>\Add<controller>Controller');
                // post | comment | answer| topic |space
                Route::get('/{controller}/edit')->controller('<controller>\Edit<controller>Controller');
            Route::endProtect(); // Завершение защиты
    Route::endType();  // Завершение getType('post')

    Route::get('/post/img/{id}/remove')->controller('Post\EditPostController@imgPostRemove')->where(['id' => '[0-9]+']);
    // Форма добавления, общий случай: post | topic | space | web
    Route::get('/{controller}/add')->controller('<controller>\Add<controller>Controller@add');
    // Из пространства
    Route::get('/post/add/space/{space_id}')->controller('Post\AddPostController@add')->where(['space_id' => '[0-9]+']);
    // Форма изменения, общий случай: post | topic | space | answer
    Route::get('/{controller}/edit/{id}')->controller('<controller>\Edit<controller>Controller@edit')->where(['id' => '[0-9]+']);
   
    Route::get('/u/{login}/invitation')->controller('UserController@invitationPage')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/u/{login}/setting')->controller('UserController@settingForm')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/u/{login}/setting/avatar')->controller('UserController@settingAvatarForm')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/setting/security')->controller('UserController@settingSecurityForm')->where(['login' => '[A-Za-z0-9]+']); 
    
    Route::get('/u/{login}/delete/cover')->controller('UserController@userCoverRemove')->where(['login' => '[A-Za-z0-9]+']); 

    Route::get('/logout')->controller('AuthController@logout');

    Route::get('/u/{login}/messages')->controller('MessagesController')->where(['login' => '[A-Za-z0-9]+']);   
    Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
    Route::get('/u/{login}/mess')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

	Route::get('/u/{login}/notifications')->controller('NotificationsController')->where(['login' => '[A-Za-z0-9]+']); 
    Route::get('/notifications/read/{id}')->controller('NotificationsController@read')->where(['id' => '[0-9]+']);  
    Route::get('/notifications/delete')->controller('NotificationsController@remove');  
    
    Route::get('/u/{login}/favorite')->controller('UserController@userFavorites')->where(['login' => '[A-Za-z0-9]+']);
    Route::get('/u/{login}/drafts')->controller('UserController@userDrafts')->where(['login' => '[A-Za-z0-9]+']);

    Route::get('/space/logo/{slug}/edit')->controller('Space\EditSpaceController@logo')->where(['slug' => '[A-Za-z0-9_]+']);  
    Route::get('/space/{slug}/delete/cover')->controller('Space\EditSpaceController@coverRemove')->where(['slug' => '[A-Za-z0-9_]+']);
    Route::get('/space/my')->controller('Space\SpaceController@spaseUser');
    Route::get('/space/my/page/{page?}')->controller('Space\SpaceController@spaseUser')->where(['page' => '[0-9]+']);
 
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
    Route::get('/post/shown')->controller('Post\PostController@shownPost');
    // Вызов формы комментария и поиск
    Route::get('/comments/addform')->controller('Comment\AddCommentController@add');
Route::endType();

// Другие страницы без авторизии
Route::get('/post/{id}')->controller('Post\PostController')->where(['id' => '[0-9-]+']);
Route::get('/post/{id}/{slug}')->controller('Post\PostController')->where(['id' => '[0-9-]+', 'slug' => '[A-Za-z0-9-_]+']);

Route::get('/info')->controller('InfoController');
Route::get('/info/privacy')->controller('InfoController@privacy');
Route::get('/info/restriction')->controller('InfoController@restriction');

Route::get('/users')->controller('UserController');
Route::get('/users/page/{page?}')->controller('UserController')->where(['page' => '[0-9]+']);
Route::get('/u/{login}')->controller('UserController@profile')->where(['login' => '[A-Za-z0-9]+']);

Route::get('/u/{login}/posts')->controller('Post\PostController@posts', ['feed'])->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/answers')->controller('Answer\AnswerController@userAnswers')->where(['login' => '[A-Za-z0-9]+']);
Route::get('/u/{login}/comments')->controller('Comment\CommentController@userComments')->where(['login' => '[A-Za-z0-9]+']);

Route::get('/comments')->controller('Comment\CommentController');
Route::get('/comments/page/{page?}')->controller('Comment\CommentController')->where(['page' => '[0-9]+']);
Route::get('/answers')->controller('Answer\AnswerController');
Route::get('/answers/page/{page?}')->controller('Answer\AnswerController')->where(['page' => '[0-9]+']);

Route::get('/spaces')->controller('Space\SpaceController');
Route::get('/spaces/page/{page?}')->controller('Space\SpaceController')->where(['page' => '[0-9]+']);
Route::get('/s/{slug}')->controller('Space\SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/page/{page?}')->controller('Space\SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);
Route::get('/s/{slug}/top')->controller('Space\SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/top/page/{page?}')->controller('Space\SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);

Route::get('/moderations')->controller('ActionController@moderation');

Route::get('/topics')->controller('Topic\TopicController')->where(['page' => '[0-9]+']);
Route::get('/topics/page/{page?}')->controller('Topic\TopicController')->where(['page' => '[0-9]+']);
Route::get('/topic/{slug}')->controller('Topic\TopicController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/topic/{slug}/page/{page?}')->controller('Topic\TopicController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/info')->controller('Topic\TopicController@info')->where(['slug' => '[A-Za-z0-9-]+']);

Route::get('/web')->controller('Web\WebController');
Route::get('/web/page/{page?}')->controller('Web\WebController')->where(['page' => '[0-9]+']);
Route::get('/domain/{domain}')->controller('Web\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+']);
Route::get('/domain/{domain}/page/{page?}')->controller('Web\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+', 'page' => '[0-9]+']);

Route::get('/')->controller('HomeController', ['feed']);
Route::get('/page/{page?}')->controller('HomeController', ['feed'])->where(['page' => '[0-9]+']);
Route::get('/top')->controller('HomeController', ['top']);
Route::get('/top/page/{page?}')->controller('HomeController', ['top'])->where(['page' => '[0-9]+']);

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/space/{id}')->controller('RssController@turboFeed')->where(['id' => '[0-9]+']);

Route::type(['get', 'post'])->get('/search')->controller('SearchController');

require 'admin.php';