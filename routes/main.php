<?php
// https://phphleb.ru/ru/v1/groups/

Route::before('Authorization@noAuth')->getGroup();
    Route::getType('post');
        Route::get('/flag/repost')->controller('ReportController');
        Route::get('/backend/upload/image')->controller('Post\EditPostController@uploadContentImage');
        Route::get('/status/action')->controller('ActionController@deletingAndRestoring');
        Route::get('/post/grabtitle')->controller('Post\AddPostController@grabMeta');
        Route::get('/comment/editform')->controller('Comment\EditCommentController@edit');
        Route::get('/post/add/profile')->controller('Post\PostController@addPostProfile');
        Route::get('/post/delete/profile')->controller('Post\PostController@deletePostProfile');
        Route::get('/favorite/post')->controller('FavoriteController', ['post']);
        Route::get('/favorite/answer')->controller('FavoriteController', ['answer']);
        Route::get('/focus/{type}')->controller('SubscriptionController')->where(['type' => '[a-z]+']);
        // @ users | posts | topics | mains 
        Route::get('/search/{type}')->controller('ActionController@select')->where(['type' => '[a-z]+']);
        // @ post | answer | comment | link
        Route::get('/votes/{type}')->controller('VotesController')->where(['type' => '[a-z]+']); 
        
            Route::getProtect(); // Начало защиты
                Route::get('/invitation/create')->controller('User\InvitationsUserController@invitationCreate');
                Route::get('/messages/send')->controller('MessagesController@send');
                Route::get('/space/logo/edit')->controller('Space\EditSpaceController@logoEdit');
                Route::get('/users/setting/edit')->controller('User\SettingController@edit');
                Route::get('/users/setting/avatar/edit')->controller('User\SettingController@avatarEdit');
                Route::get('/users/setting/security/edit')->controller('User\SettingController@securityEdit');
                Route::get('/users/setting/avatar/edit')->controller('User\SettingController@avatarEdit');
                Route::get('/users/setting/notifications/edit')->controller('User\SettingController@notificationsEdit');
                // post | comment | answer| topic | space
                Route::get('/{controller}/create')->controller('<controller>\Add<controller>Controller');
                // post | comment | answer| topic |space
                Route::get('/{controller}/edit')->controller('<controller>\Edit<controller>Controller');
            Route::endProtect(); // Завершение защиты
    Route::endType();  // Завершение getType('post')

    // Форма добавления, общий случай: post | topic | space | web
    Route::get('/{controller}/add')->controller('<controller>\Add<controller>Controller@add');
    // Из пространства
    Route::get('/post/add/space/{space_id}')->controller('Post\AddPostController@add')->where(['space_id' => '[0-9]+']);
    // Форма изменения, общий случай: post | topic | space | answer
    Route::get('/{controller}/edit/{id}')->controller('<controller>\Edit<controller>Controller@edit')->where(['id' => '[0-9]+']);

    Route::get('/u/{login}/setting')->controller('User\SettingController@settingForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting'); 
    Route::get('/u/{login}/setting/avatar')->controller('User\SettingController@avatarForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.avatar');
    Route::get('/u/{login}/setting/security')->controller('User\SettingController@securityForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.security');
    Route::get('/u/{login}/setting/notifications')->controller('User\SettingController@notificationsForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.notifications');

    Route::get('/post/img/{id}/remove')->controller('Post\EditPostController@imgPostRemove')->where(['id' => '[0-9]+']);
    Route::get('/u/{login}/delete/cover')->controller('User\SettingController@userCoverRemove')->where(['login' => '[A-Za-z0-9]+']); 

    Route::get('/logout')->controller('Auth\LogoutController')->name('logout');

    Route::get('/u/{login}/messages')->controller('MessagesController')->where(['login' => '[A-Za-z0-9]+'])->name('messages');   
    Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+']); 
    Route::get('/u/{login}/mess')->controller('MessagesController@profilMessages')->where(['login' => '[A-Za-z0-9]+']); 

	Route::get('/u/{login}/notifications')->controller('NotificationsController')->where(['login' => '[A-Za-z0-9]+'])->name('notifications'); 
    Route::get('/notifications/read/{id}')->controller('NotificationsController@read')->where(['id' => '[0-9]+']);  
    Route::get('/notifications/delete')->controller('NotificationsController@remove');  
    
    Route::get('/u/{login}/favorites')->controller('User\UserController@userFavorites')->where(['login' => '[A-Za-z0-9]+'])->name('favorites');
    Route::get('/u/{login}/subscribed')->controller('User\UserController@subscribedPage')->where(['login' => '[A-Za-z0-9]+'])->name('subscribed');

    Route::get('/u/{login}/drafts')->controller('User\UserController@userDrafts')->where(['login' => '[A-Za-z0-9]+'])->name('drafts');
    
    Route::get('/u/{login}/invitations')->controller('User\InvitationsUserController@invitationForm')->where(['login' => '[A-Za-z0-9]+'])->name('invitations');

    Route::get('/space/logo/{slug}/edit')->controller('Space\EditSpaceController@logo')->where(['slug' => '[A-Za-z0-9_]+']);  
    Route::get('/space/{slug}/delete/cover')->controller('Space\EditSpaceController@coverRemove')->where(['slug' => '[A-Za-z0-9_]+']);
    Route::get('/space/my')->controller('Space\SpaceController@spaseUser');
    Route::get('/space/my/page/{page?}')->controller('Space\SpaceController@spaseUser')->where(['page' => '[0-9]+']);
 
    Route::get('/all')->controller('HomeController', ['all']);
    Route::get('/all/page/{page?}')->controller('HomeController', ['all'])->where(['page' => '[0-9]+']);
    
    Route::get('/welcome')->controller('WelcomeController')->name('welcome');
Route::endGroup();

Route::before('Authorization@yesAuth')->getGroup();
    Route::getType('post');
        Route::getProtect();
            Route::get('/recover/send')->controller('Auth\RecoverController'); 
            Route::get('/recover/send/pass')->controller('Auth\RecoverController@remindNew'); 
            Route::get('/register/add')->controller('Auth\RegisterController');
            Route::get('/login')->controller('Auth\LoginController');
        Route::endProtect();
    Route::endType();

    Route::get('/invite')->controller('User\InvitationsUserController@inviteForm')->name('invite');
	Route::get('/register')->controller('Auth\RegisterController@showRegisterForm')->name('register');
    
    Route::getType('get');
        Route::get('/register/invite/{code}')->controller('Auth\RegisterController@showInviteForm')->where(['code' => '[a-z0-9-]+']);
        Route::get('/recover')->controller('Auth\RecoverController@showPasswordForm')->name('recover');  
        Route::get('/recover/remind/{code}')->controller('Auth\RecoverController@showRemindForm')->where(['code' => '[A-Za-z0-9-]+']);
        Route::get('/email/activate/{code}')->controller('Auth\RecoverController@ActivateEmail')->where(['code' => '[A-Za-z0-9-]+']);
        Route::get('/login')->controller('Auth\LoginController@showLoginForm')->name('login');
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
Route::get('/post/{id}/{slug}')->controller('Post\PostController')->where(['id' => '[0-9-]+', 'slug' => '[A-Za-z0-9-_]+'])->name('post');

Route::get('/info')->controller('InfoController')->name('info');
Route::get('/info/privacy')->controller('InfoController@privacy')->name('info.privacy');
Route::get('/info/restriction')->controller('InfoController@restriction')->name('info.restriction');

Route::get('/users')->controller('User\UserController')->name('users');
Route::get('/users/page/{page?}')->controller('User\UserController')->where(['page' => '[0-9]+']);

Route::get('/u/{login}')->controller('User\UserController@profile')->where(['login' => '[A-Za-z0-9]+'])->name('user');
Route::get('/u/{login}/posts')->controller('Post\PostController@posts', ['feed'])->where(['login' => '[A-Za-z0-9]+'])->name('posts.user');
Route::get('/u/{login}/answers')->controller('Answer\AnswerController@userAnswers')->where(['login' => '[A-Za-z0-9]+'])->name('answers.user');
Route::get('/u/{login}/comments')->controller('Comment\CommentController@userComments')->where(['login' => '[A-Za-z0-9]+'])->name('comments.user');

Route::get('/comments')->controller('Comment\CommentController')->name('comments');
Route::get('/comments/page/{page?}')->controller('Comment\CommentController')->where(['page' => '[0-9]+']);
Route::get('/answers')->controller('Answer\AnswerController')->name('answers');
Route::get('/answers/page/{page?}')->controller('Answer\AnswerController')->where(['page' => '[0-9]+']);

Route::get('/spaces')->controller('Space\SpaceController')->name('spaces');
Route::get('/spaces/page/{page?}')->controller('Space\SpaceController')->where(['page' => '[0-9]+']);
Route::get('/s/{slug}')->controller('Space\SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+'])->name('space');
Route::get('/s/{slug}/page/{page?}')->controller('Space\SpaceController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);
Route::get('/s/{slug}/top')->controller('Space\SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/top/page/{page?}')->controller('Space\SpaceController@posts', ['top'])->where(['slug' => '[A-Za-z0-9_]+', 'page' => '[0-9]+']);
Route::get('/s/{slug}/writers')->controller('Space\SpaceController@posts', ['writers'])->where(['slug' => '[A-Za-z0-9_]+']);
Route::get('/s/{slug}/wiki')->controller('Space\SpaceController@wiki')->where(['slug' => '[A-Za-z0-9_]+']);

Route::get('/moderations')->controller('ActionController@moderation')->name('moderation');

Route::get('/topics')->controller('Topic\TopicController')->where(['page' => '[0-9]+'])->name('topics');
Route::get('/topics/page/{page?}')->controller('Topic\TopicController')->where(['page' => '[0-9]+']);
Route::get('/topic/{slug}')->controller('Topic\TopicController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9-]+'])->name('topic');
Route::get('/topic/{slug}/page/{page?}')->controller('Topic\TopicController@posts', ['feed'])->where(['slug' => '[A-Za-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/info')->controller('Topic\TopicController@info')->where(['slug' => '[A-Za-z0-9-]+']);

Route::get('/web')->controller('Web\WebController')->name('web');
Route::get('/web/page/{page?}')->controller('Web\WebController')->where(['page' => '[0-9]+']);
Route::get('/domain/{domain}')->controller('Web\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+'])->name('domain');
Route::get('/domain/{domain}/page/{page?}')->controller('Web\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+', 'page' => '[0-9]+']);

Route::get('/')->controller('HomeController', ['feed']);
Route::get('/page/{page?}')->controller('HomeController', ['feed'])->where(['page' => '[0-9]+']);
Route::get('/top')->controller('HomeController', ['top']);
Route::get('/top/page/{page?}')->controller('HomeController', ['top'])->where(['page' => '[0-9]+']);

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/space/{id}')->controller('RssController@turboFeed')->where(['id' => '[0-9]+']);

Route::type(['get', 'post'])->get('/search')->controller('SearchController')->name('search');

require 'admin.php';