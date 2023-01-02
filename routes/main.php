<?php

// https://phphleb.ru/ru/v1/types/
Route::before('Designator', [UserData::USER_FIRST_LEVEL, '>='])->getGroup();
    Route::post('/backend/upload/{type}/{id}')->controller('Post\EditPostController@uploadContentImage')->where(['type' => '[a-z-]+', 'id' => '[0-9]+']);
    Route::post('/status/action')->controller('ActionController@deletingAndRestoring');
    Route::post('/post/grabtitle')->controller('Post\AddPostController@grabMeta');
    Route::post('/comment/editform')->controller('Comment\EditCommentController');
    Route::post('/reply/editform')->controller('Item\ReplyController');
    // @ users | posts | topics | category
    Route::post('/search/{type}')->controller('SearchController@select')->where(['type' => '[a-z]+']);
     
    Route::getProtect();
        Route::before('Restrictions')->getGroup();
            Route::post('/user/edit/{type}')->controller('User\SettingController@change')->where(['type' => '[a-z]+'])->name('setting.change');
            // Отправка / изменение контента
            Route::post('/create/{type}')->controller('FormController@create')->name('content.create');
            Route::post('/change/{type}')->controller('FormController@change')->name('content.change');
            Route::post('/team/change/{type}/{id}')->controller('Facets\TeamFacetController@change')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('team.change');
        Route::endProtect();
    Route::endGroup();       

    // Формы добавления и изменения
    Route::before('Restrictions')->getGroup();
        Route::get('/add/{type}')->controller('FormController@add')->where(['type' => '[a-z]+'])->name('content.add');
        Route::get('/edit/{type}/{id}')->controller('FormController@edit')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('content.edit');
        Route::get('/team/edit/{type}/{id}')->controller('Facets\TeamFacetController')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('team.edit');
        Route::get('/setting/{type?}')->controller('User\SettingController')->where(['type' => '[a-z_]+'])->name('setting'); 
    Route::endGroup();    

    Route::type(['get', 'post'])->get('/folder/content/save')->controller('FolderController@addFolderContent');
 
    Route::get('/add/post/{topic_id}')->controller('Post\AddPostController', ['post'])->where(['topic_id' => '[0-9]+']);

    Route::get('/post/img/{id}/remove')->controller('Post\EditPostController@imgPostRemove')->where(['id' => '[0-9]+']);

    Route::get('/web/bookmarks')->controller('Item\UserAreaController@bookmarks')->name('web.bookmarks');
    Route::get('/web/my')->controller('Item\UserAreaController')->name('web.user.sites');

    Route::get('/messages')->controller('MessagesController')->name('messages');   
    Route::get('/messages/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+'])->name('dialogues'); 
    Route::get('/messages/@{login}')->controller('MessagesController@messages')->where(['login' => '[A-Za-z0-9]+'])->name('send.messages');
    
	Route::get('/notifications')->controller('NotificationController')->name('notifications'); 
    Route::get('/notification/{id}')->controller('NotificationController@read')->where(['id' => '[0-9]+'])->name('notif.read');  
    Route::get('/notifications/delete')->controller('NotificationController@remove')->name('notif.remove');  
    Route::get('/favorites')->controller('User\UserController@favorites')->name('favorites');
    Route::get('/favorites/folders')->controller('User\UserController@folders')->name('favorites.folders');
    Route::get('/favorites/folders/{id}')->controller('User\UserController@foldersFavorite')->where(['id' => '[0-9]+'])->name('favorites.folder.id');
    Route::get('/subscribed')->controller('User\UserController@subscribed')->name('subscribed');
    Route::get('/read')->controller('User\UserController@read')->name('read');
    Route::get('/drafts')->controller('User\UserController@drafts')->name('drafts');
    Route::get('/invitations')->controller('User\InvitationsController@invitationForm')->name('invitations');

    Route::get('/logout')->controller('Auth\LogoutController')->name('logout');
    Route::get('/topics/my')->controller('Facets\AllFacetController', ['my', 'topic'])->name('topics.my');
    Route::get('/post/scroll/{type}')->controller('HomeController@scroll'); 
    Route::get('/blogs/my')->controller('Facets\AllFacetController', ['my', 'blog'])->name('blogs.my');
    Route::get('/all')->controller('HomeController', ['all'])->name('main.all');
Route::endGroup();

Route::before('Designator', [UserData::USER_ZERO_LEVEL, '='])->getGroup();
    Route::getProtect();
        Route::post('/recover/send')->controller('Auth\RecoverController')->name('recover.send'); 
        Route::post('/recover/send/pass')->controller('Auth\RecoverController@remindNew')->name('new.pass'); 
        Route::post('/register/add')->controller('Auth\RegisterController')->name('register.add');
        Route::post('/login')->controller('Auth\LoginController')->name('enterLogin');
    Route::endProtect();

    Route::get('/invite')->controller('User\InvitationsController@inviteForm')->name('invite');
	Route::get('/register')->controller('Auth\RegisterController@showRegisterForm')->name('register');
    
    Route::get('/register/invite/{code}')->controller('Auth\RegisterController@showInviteForm')->where(['code' => '[a-z0-9-]+'])->name('invite.reg');
    Route::get('/recover')->controller('Auth\RecoverController@showPasswordForm')->name('recover');  
    Route::get('/recover/remind/{code}')->controller('Auth\RecoverController@showRemindForm')->where(['code' => '[A-Za-z0-9-]+'])->name('recover.code');
    Route::get('/email/activate/{code}')->controller('Auth\RecoverController@ActivateEmail')->where(['code' => '[A-Za-z0-9-]+'])->name('activate.code');
    Route::get('/login')->controller('Auth\LoginController@showLoginForm')->name('login');
Route::endGroup();

Route::getProtect();
    Route::post('/comments/addform')->controller('Comment\AddCommentController');
    Route::post('/reply/addform')->controller('Item\ReplyController@addForma');
Route::endProtect();    
  
Route::get('/search')->controller('SearchController', ['post'])->name('search');
Route::get('/search/go')->controller('SearchController@go', ['post'])->name('search.go');

// Other pages without authorization
Route::get('/post/{id}')->controller('Post\PostController', ['post'])->where(['id' => '[0-9]+'])->name('post_id');;
Route::get('/post/{id}/{slug}')->controller('Post\PostController', ['post'])->where(['id' => '[0-9]+', 'slug' => '[A-Za-z0-9-_]+'])->name('post');

// Страницы info
Route::get('/{facet_slug}/article/{slug}')->controller('Post\PostController', ['info.page'])->where(['facet_slug' => '[A-Za-z0-9-_]+', 'slug' => '[A-Za-z0-9-_]+'])->name('facet.article'); 

Route::get('/users/new')->controller('User\UserController', ['new'])->name('users.new');
Route::get('/users')->controller('User\UserController', ['all'])->name('users.all');

Route::get('/@{login}')->controller('User\ProfileController')->where(['login' => '[A-Za-z0-9]+'])->name('profile');
Route::get('/@{login}/posts')->controller('User\ProfileController@posts')->where(['login' => '[A-Za-z0-9]+'])->name('profile.posts');
Route::get('/@{login}/comments')->controller('User\ProfileController@comments')->where(['login' => '[A-Za-z0-9]+'])->name('profile.comments');

Route::get('/comments')->controller('Comment\CommentController', ['all'])->name('comments');

Route::get('/topics/new')->controller('Facets\AllFacetController', ['new', 'topic'])->name('topics.new');
Route::get('/topic/{slug}/recommend')->controller('Facets\TopicFacetController', ['recommend', 'topics'])->where(['slug' => '[a-z0-9-]+'])->name('recommend');
Route::get('/topic/{slug}/questions')->controller('Facets\TopicFacetController', ['questions', 'topics'])->where(['slug' => '[a-z0-9-]+'])->name('questions');
Route::get('/topic/{slug}/posts')->controller('Facets\TopicFacetController', ['posts', 'topics'])->where(['slug' => '[a-z0-9-]+'])->name('posts');
Route::get('/topic/{slug}/info')->controller('Facets\TopicFacetController@info')->where(['slug' => '[a-z0-9-]+'])->name('topic.info');
Route::get('/topic/{slug}/writers')->controller('Facets\TopicFacetController@writers')->where(['slug' => '[a-z0-9-]+'])->name('topic.writers');
Route::get('/topics')->controller('Facets\AllFacetController', ['all', 'topic'])->name('topics.all');

Route::get('/topic/{slug}')->controller('Facets\TopicFacetController', ['facet.feed', 'topics'])->where(['slug' => '[a-z0-9-]+'])->name('topic');

Route::get('/blogs/new')->controller('Facets\AllFacetController', ['new', 'blog'])->name('blogs.new');
Route::get('/blogs')->controller('Facets\AllFacetController', ['all', 'blog'])->name('blogs.all');

Route::get('/blog/{slug}/read')->controller('Facets\ReadController')->where(['slug' => '[a-z0-9-]+'])->name('blog.read');
Route::get('/blog/{slug}/posts')->controller('Facets\BlogFacetController', ['posts', 'blog'])->where(['slug' => '[a-z0-9-]+'])->name('blog.posts');
Route::get('/blog/{slug}/questions')->controller('Facets\BlogFacetController', ['questions', 'blog'])->where(['slug' => '[a-z0-9-]+'])->name('blog.questions');
 

Route::get('/blog/{slug}')->controller('Facets\BlogFacetController', ['facet.feed', 'blog.user'])->where(['slug' => '[a-z0-9-]+'])->name('blog');

Route::get('/redirect/facet/{id}')->controller('Facets\RedirectController')->where(['id' => '[0-9]+'])->name('redirect.facet');

Route::get('/domain/{domain}')->controller('Post\PostController@domain', ['web.feed'])->where(['domain' => '[a-z0-9-.]+'])->name('domain');

Route::get('/web')->controller('Item\HomeController', ['main'])->name('web');
Route::get('/web/website/{slug}')->controller('Item\DetailedController')->name('website');
Route::get('/web/dir/{sort}/{slug}')->controller('Item\DirController')->name('category');
Route::get('/web/{grouping}/dir/{sort}/{slug}')->controller('Item\DirController')->name('grouping.category');

Route::type(['get', 'post'])->get('/cleek')->controller('Item\DirController@cleek');

Route::get('/')->controller('HomeController', ['feed'])->name('main'); 
Route::get('/top')->controller('HomeController', ['top'])->name('main.top');
Route::get('/questions')->controller('HomeController', ['questions'])->name('main.questions');
Route::get('/posts')->controller('HomeController', ['posts'])->name('main.posts');

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/topic/{slug}')->controller('RssController@turboFeed')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/rss-feed/topic/{slug}')->controller('RssController@rssFeed')->where(['slug' => '[A-Za-z0-9-]+']);

// Route::bottleneck('/attention', true, "TECHNICAL WORKS ON THE SERVER");

require 'admin.php';