<?php
// https://phphleb.ru/ru/v1/groups/
Route::before('Designator', [UserData::USER_FIRST_LEVEL, '>='])->getGroup();
    Route::getType('post');
        Route::get('/flag/repost')->controller('AuditController@report');
        Route::get('/backend/upload/{type}/{id}')->controller('Post\EditPostController@uploadContentImage')->where(['type' => '[a-z-]+', 'id' => '[0-9]+']);
        Route::get('/status/action')->controller('ActionController@deletingAndRestoring');
        Route::get('/post/recommend')->controller('Post\AddPostController@recommend');
        Route::get('/post/grabtitle')->controller('Post\AddPostController@grabMeta');
        Route::get('/comment/editform')->controller('Comment\EditCommentController');
        Route::get('/post/profile')->controller('Post\PostController@postProfile');
        Route::get('/favorite/add')->controller('FavoriteController');
        Route::get('/focus')->controller('SubscriptionController');
        // @ users | posts | topics | category
        Route::get('/search/{type}')->controller('ActionController@select')->where(['type' => '[a-z]+']);
        Route::get('/votes')->controller('VotesController'); 
            Route::getProtect();
                Route::get('/invitation/create')->controller('User\InvitationsController@create')->name('invit.create');
                Route::get('/messages/send')->controller('MessagesController@send')->name('messages.send');
                Route::get('/users/setting/edit')->controller('User\SettingController@edit')->name('setting.edit');
                Route::get('/users/setting/avatar/edit')->controller('User\SettingController@avatarEdit')->name('setting.avatar.edit');
                Route::get('/users/setting/security/edit')->controller('User\SettingController@securityEdit')->name('setting.security.edit');
                Route::get('/users/setting/notification/edit')->controller('User\SettingController@notificationEdit')->name('setting.notif.edit');
                
                // Add / Edit: post | comment | answer | facet | web
                Route::get('/web/create')->module('catalog', 'App\Add@create')->name('create.web');
                Route::get('/create/{type}')->controller('ActionController@create')->where(['type' => '[a-z]+'])->name('content.create');
                Route::get('/change/web')->module('catalog', 'App\Edit@edit')->name('change.web');
                Route::get('/change/{type}')->controller('ActionController@change')->where(['type' => '[a-z]+'])->name('content.change');
            Route::endProtect();
    Route::endType();

    // Pages (forms) for adding and changing: (post | page | answer), (topic | category | blog | sections) and site
    Route::get('/web/add')->module('catalog', 'App\Add')->name('web.add');    
    Route::get('/add/{type}')->controller('ActionController@add')->where(['type' => '[a-z]+'])->name('content.add');
    Route::get('/web/edit/{id}')->module('catalog', 'App\Edit')->where(['id' => '[0-9]+'])->name('web.edit');
    Route::get('/edit/{type}/{id}')->controller('ActionController@edit')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('content.edit');
    // end

    Route::get('/add/post/{topic_id}')->controller('Post\AddPostController', ['post'])->where(['topic_id' => '[0-9]+']);
    Route::get('/add/page/{topic_id}')->controller('Post\AddPostController', ['page'])->where(['topic_id' => '[0-9]+']);

    Route::get('/post/img/{id}/remove')->controller('Post\EditPostController@imgPostRemove')->where(['id' => '[0-9]+']);
    Route::get('/@{login}/delete/cover')->controller('User\SettingController@coverRemove')->where(['login' => '[A-Za-z0-9]+'])->name('delete.cover'); 
    Route::get('/@{login}/messages')->controller('MessagesController@messages')->where(['login' => '[A-Za-z0-9]+'])->name('send.messages');

    Route::get('/web/bookmarks')->module('catalog', 'App\UserArea@bookmarks', ['web.bookmarks', 'web'])->name('web.bookmarks');
    Route::get('/web/my')->module('catalog', 'App\UserArea', ['web.user.sites', 'sites'])->name('web.user.sites');
    Route::get('/web/my/page/{page?}')->module('catalog', 'App\UserArea', ['web.user.sites', 'sites'])->where(['page' => '[0-9]+']);

    Route::get('/setting')->controller('User\SettingController@settingForm')->name('setting'); 
    Route::get('/setting/avatar')->controller('User\SettingController@avatarForm')->name('setting.avatar');
    Route::get('/setting/security')->controller('User\SettingController@securityForm')->name('setting.security');
    Route::get('/setting/notifications')->controller('User\SettingController@notificationForm')->name('setting.notifications');
    Route::get('/messages')->controller('MessagesController')->name('messages');   
    Route::get('/messages/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+'])->name('dialogues'); 
	Route::get('/notifications')->controller('NotificationController')->name('notifications'); 
    Route::get('/notification/{id}')->controller('NotificationController@read')->where(['id' => '[0-9]+'])->name('notif.read');  
    Route::get('/notifications/delete')->controller('NotificationController@remove')->name('notif.remove');  
    Route::get('/favorites')->controller('User\UserController@favorites')->name('favorites');
    Route::get('/subscribed')->controller('User\UserController@subscribed')->name('subscribed');
    Route::get('/drafts')->controller('User\UserController@drafts')->name('drafts');
    Route::get('/invitations')->controller('User\InvitationsController@invitationForm')->name('invitations');

    Route::get('/logout')->controller('Auth\LogoutController')->name('logout');

    Route::get('/topics/my')->controller('Facets\AllFacetController', ['topics.my', 'topic'])->name('topics.my');
    Route::get('/topics/my/page/{page?}')->controller('Facets\AllFacetController', ['topics.my', 'topic'])->where(['page' => '[0-9]+']); 
 
    Route::get('/blogs/my')->controller('Facets\AllFacetController', ['blogs.my', 'blog'])->name('blogs.my');
 
    Route::get('/all')->controller('HomeController', ['main.all', 'main'])->name('main.all');
    Route::get('/all/page/{page?}')->controller('HomeController', ['main.all', 'main'])->where(['page' => '[0-9]+']);
    
    Route::get('/deleted')->controller('HomeController', ['main.deleted', 'main'])->name('main.deleted');
    Route::get('/deleted/page/{page?}')->controller('HomeController', ['main.deleted', 'main'])->where(['page' => '[0-9]+']);
Route::endGroup();

Route::before('Designator', [UserData::USER_ZERO_LEVEL, '='])->getGroup();
    Route::getType('post');
        Route::getProtect();
            Route::get('/recover/send')->controller('Auth\RecoverController')->name('recover.send'); 
            Route::get('/recover/send/pass')->controller('Auth\RecoverController@remindNew'); 
            Route::get('/register/add')->controller('Auth\RegisterController')->name('register.add');
            Route::get('/login')->controller('Auth\LoginController');
        Route::endProtect();
    Route::endType();

    Route::get('/invite')->controller('User\InvitationsController@inviteForm')->name('invite');
	Route::get('/register')->controller('Auth\RegisterController@showRegisterForm')->name('register');
    
    Route::getType('get');
        Route::get('/register/invite/{code}')->controller('Auth\RegisterController@showInviteForm')->where(['code' => '[a-z0-9-]+'])->name('invite.reg');
        Route::get('/recover')->controller('Auth\RecoverController@showPasswordForm')->name('recover');  
        Route::get('/recover/remind/{code}')->controller('Auth\RecoverController@showRemindForm')->where(['code' => '[A-Za-z0-9-]+'])->name('recover.code');
        Route::get('/email/activate/{code}')->controller('Auth\RecoverController@ActivateEmail')->where(['code' => '[A-Za-z0-9-]+'])->name('activate.code');
        Route::get('/login')->controller('Auth\LoginController@showLoginForm')->name('login');
    Route::endType();
Route::endGroup();

Route::getType('post');
    Route::get('/post/shown')->controller('Post\PostController@shownPost');
    Route::get('/user/card')->controller('User\UserController@card');
    Route::get('/msg/go')->controller('Post\AddPostController@msg');
    Route::get('/comments/addform')->controller('Comment\AddCommentController');
Route::endType();
  
Route::type(['get', 'post'])->get('/topic/{slug}/followers/{id}')->controller('Facets\TopicFacetController@followers')->where(['slug' => '[a-z0-9-]+', 'id' => '[0-9]+'])->name('topic.followers');  
  
// Other pages without authorization
Route::get('/post/{id}')->controller('Post\PostController')->where(['id' => '[0-9]+']);
Route::get('/post/{id}/{slug}')->controller('Post\PostController')->where(['id' => '[0-9]+', 'slug' => '[A-Za-z0-9-_]+'])->name('post');

Route::get('/users')->controller('User\UserController', ['users.all', 'user'])->name('users.all');
Route::get('/users/page/{page?}')->controller('User\UserController', ['users.all', 'user'])->where(['page' => '[0-9]+']);
Route::get('/users/new')->controller('User\UserController', ['users.new', 'user'])->name('users.new');
Route::get('/users/new/page/{page?}')->controller('User\UserController', ['users.new', 'user'])->where(['page' => '[0-9]+']);

Route::get('/@{login}')->controller('User\ProfileController', ['profile.posts', 'profile'])->where(['login' => '[A-Za-z0-9]+'])->name('profile');
Route::get('/@{login}/posts')->controller('User\ProfileController@posts', ['profile.posts', 'profile'])->where(['login' => '[A-Za-z0-9]+'])->name('profile.posts');
Route::get('/@{login}/posts/page/{page?}')->controller('User\ProfileController@posts', ['profile.posts', 'profile'])->where(['page' => '[0-9]+', 'login' => '[A-Za-z0-9]+']);
Route::get('/@{login}/answers')->controller('User\ProfileController@answers', ['profile.answers', 'profile'])->where(['login' => '[A-Za-z0-9]+'])->name('profile.answers');
Route::get('/@{login}/answers/page/{page?}')->controller('User\ProfileController@answers', ['profile.answers', 'profile'])->where(['page' => '[0-9]+', 'login' => '[A-Za-z0-9]+']);
Route::get('/@{login}/comments')->controller('User\ProfileController@comments', ['profile.comments', 'profile'])->where(['login' => '[A-Za-z0-9]+'])->name('profile.comments');
Route::get('/@{login}/comments/page/{page?}')->controller('User\ProfileController@comments', ['profile.comments', 'profile'])->where(['page' => '[0-9]+', 'login' => '[A-Za-z0-9]+']);

Route::get('/comments')->controller('Comment\CommentController', ['comments.all', 'comments'])->name('comments');
Route::get('/comments/page/{page?}')->controller('Comment\CommentController', ['comments.all', 'comments'])->where(['page' => '[0-9]+']);
Route::get('/answers')->controller('Answer\AnswerController', ['answers.all', 'answers'])->name('answers');
Route::get('/answers/page/{page?}')->controller('Answer\AnswerController', ['answers.all', 'answers'])->where(['page' => '[0-9]+']);

Route::get('/topics')->controller('Facets\AllFacetController', ['topics.all', 'topic'])->name('topics.all');
Route::get('/topics/page/{page?}')->controller('Facets\AllFacetController', ['topics.all', 'topic'])->where(['page' => '[0-9]+']);
Route::get('/topics/new')->controller('Facets\AllFacetController', ['topics.new', 'topic'])->name('topics.new');
Route::get('/topics/new/page/{page?}')->controller('Facets\AllFacetController', ['topics.new', 'topic'])->where(['page' => '[0-9]+']);

Route::get('/topic/{slug}')->controller('Facets\TopicFacetController', ['facet.feed', 'topic'])->where(['slug' => '[a-zA-Z0-9-]+'])->name('topic');

Route::get('/topic/{slug}/recommend')->controller('Facets\TopicFacetController', ['facet.recommend', 'topic'])->where(['slug' => '[a-z0-9-]+'])->name('recommend');
Route::get('/topic/{slug}/recommend/page/{page?}')->controller('Facets\TopicFacetController', ['facet.recommend', 'topic'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/page/{page?}')->controller('Facets\TopicFacetController', ['facet.feed', 'topic'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/info')->controller('Facets\TopicFacetController@info')->where(['slug' => '[a-z0-9-]+'])->name('topic.info');
Route::get('/topic/{slug}/writers')->controller('Facets\TopicFacetController@writers')->where(['slug' => '[a-z0-9-]+'])->name('topic.writers');

Route::get('/blogs')->controller('Facets\AllFacetController', ['blogs.all', 'blog'])->name('blogs.all');
Route::get('/blogs/page/{page?}')->controller('Facets\AllFacetController', ['topics.all', 'blog'])->where(['page' => '[0-9]+']);
Route::get('/blogs/new')->controller('Facets\AllFacetController', ['blogs.new', 'blog'])->name('blogs.new');
Route::get('/blogs/new/page/{page?}')->controller('Facets\AllFacetController', ['blogs.new', 'blog'])->where(['page' => '[0-9]+']);

Route::get('/blog/{slug}')->controller('Facets\BlogFacetController', ['facet.feed', 'blog.user'])->where(['slug' => '[a-zA-Z0-9-]+'])->name('blog');
Route::get('/blog/{slug}/page/{page?}')->controller('Facets\BlogFacetController', ['facet.feed', 'blog.user'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);

// Страницы
Route::get('/blog/{slug}/article/{post_slug}')->controller('PageController', ['blog.page'])->where(['slug' => '[A-Za-z0-9-_]+', 'post_slug' => '[A-Za-z0-9-_]+'])->name('blog.article');
Route::get('/{slug}/article/{post_slug}')->controller('PageController', ['info.page'])->where(['slug' => '[A-Za-z0-9-_]+', 'post_slug' => '[A-Za-z0-9-_]+'])->name('facet.article'); 

Route::get('/domain/{domain}')->controller('Post\PostController@domain', ['web.feed', 'domain'])->where(['domain' => '[A-Za-z0-9-.]+'])->name('domain');
Route::get('/domain/{domain}/page/{page?}')->controller('Post\PostController@domain', ['web.feed', 'domain'])->where(['domain' => '[A-Za-z0-9-.]+', 'page' => '[0-9]+']);

Route::get('/web')->module('catalog', 'App\Home', ['web', 'web'])->name('web');
Route::get('/web/top')->module('catalog', 'App\Home', ['web.top', 'web'])->name('web.top');

Route::get('/web/website/{slug}')->module('catalog', 'App\Catalog@website', ['feed'])->name('web.website');

Route::get('/web/{cat}/{slug}')->module('catalog', 'App\Catalog', ['web.top', 'web'])->name('web.dir');
Route::get('/web/{cat}/{slug}/all')->module('catalog', 'App\Catalog', ['web.all', 'web'])->name('web.dir.all');
Route::get('/web/{cat}/{slug}/top')->module('catalog', 'App\Catalog', ['web.top', 'web'])->name('web.dir.top');

Route::get('/web/{cat}/top/page/{page?}')->module('catalog', 'App\Home', ['web.top', 'web']);
Route::get('/web/{cat}/{slug}/page/{page?}')->module('catalog', 'App\Catalog', ['feed', 'web']);

Route::get('/search')->module('search', 'App\Search')->name('search');

Route::type(['get', 'post'])->get('/cleek')->module('catalog', 'App\Catalog@cleek');

Route::get('/')->controller('HomeController', ['main.feed', 'main'])->name('main');
Route::get('/page/{page?}')->controller('HomeController', ['main.feed', 'main'])->where(['page' => '[0-9]+']);
Route::get('/top')->controller('HomeController', ['main.top', 'main'])->name('main.top');
Route::get('/top/page/{page?}')->controller('HomeController', ['main.top', 'main'])->where(['page' => '[0-9]+']);

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/topic/{slug}')->controller('RssController@turboFeed')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/rss-feed/topic/{slug}')->controller('RssController@rssFeed')->where(['slug' => '[A-Za-z0-9-]+']);

require 'admin.php';