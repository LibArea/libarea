<?php
// https://phphleb.ru/ru/v1/groups/

Route::before('Authorization@noAuth')->getGroup();
    Route::getType('post');
        Route::get('/flag/repost')->controller('ReportController');
        Route::get('/backend/upload/image')->controller('Post\EditPostController@uploadContentImage');
        Route::get('/status/action')->controller('ActionController@deletingAndRestoring');
        Route::get('/post/recommend')->controller('Post\AddPostController@recommend');
        Route::get('/post/grabtitle')->controller('Post\AddPostController@grabMeta');
        Route::get('/comment/editform')->controller('Comment\EditCommentController');
        Route::get('/post/add/profile')->controller('Post\PostController@addPostProfile');
        Route::get('/post/delete/profile')->controller('Post\PostController@deletePostProfile');
        Route::get('/favorite/post')->controller('FavoriteController', ['post']);
        Route::get('/favorite/answer')->controller('FavoriteController', ['answer']);
        Route::get('/focus/{type}')->controller('SubscriptionController')->where(['type' => '[a-z]+']);
        // @ users | posts | topics
        Route::get('/search/{type}')->controller('ActionController@select')->where(['type' => '[a-z]+']);
        // @ post | answer | comment | link
        Route::get('/votes/{type}')->controller('VotesController')->where(['type' => '[a-z]+']); 
            Route::getProtect();
                Route::get('/invitation/create')->controller('User\InvitationsController@create')->name('invit.create');
                Route::get('/messages/send')->controller('MessagesController@send')->name('messages.send');
                Route::get('/users/setting/edit')->controller('User\SettingController@edit')->name('setting.edit');
                Route::get('/users/setting/avatar/edit')->controller('User\SettingController@avatarEdit')->name('setting.avatar.edit');
                Route::get('/users/setting/security/edit')->controller('User\SettingController@securityEdit')->name('setting.security.edit');
                Route::get('/users/setting/notifications/edit')->controller('User\SettingController@notificationsEdit')->name('setting.notif.edit');
                // Add / Edit: post | comment | answer | topic | web
                Route::get('/post/create')->controller('Post\AddPostController@create')->name('post.create');
                Route::get('/comment/create')->controller('Comment\AddCommentController@create')->name('comment.create');
                Route::get('/answer/create')->controller('Answer\AddAnswerController@create')->name('answer.create');
                Route::get('/web/create')->controller('Item\AddWebController@create')->name('web.create');
                Route::get('/topic/create')->controller('Facets\AddFacetController@create', ['topic'])->name('topic.create');
                Route::get('/blog/create')->controller('Facets\AddFacetController@create', ['blog'])->name('blog.create');
                Route::get('/post/edit')->controller('Post\EditPostController@edit')->name('post.edit.pr');
                Route::get('/comment/edit')->controller('Comment\EditCommentController@edit')->name('comment.edit.pr');
                Route::get('/answer/edit')->controller('Answer\EditAnswerController@edit')->name('answer.edit.pr');
                Route::get('/web/edit')->controller('Item\EditWebController@edit')->name('web.edit.pr');
                Route::get('/topic/edit')->controller('Facets\EditFacetController@edit', ['topic'])->name('topic.edit.pr');
                Route::get('/blog/edit')->controller('Facets\EditFacetController@edit', ['blog'])->name('blog.edit.pr');
            Route::endProtect();
    Route::endType();

    Route::get('/search/{type}/{q}')->controller('ActionController@select')->where(['type' => '[a-z]+', 'q' => '[a-zA-Zа-яА-Я0-8 ]+']);

    // The form of adding and changing: post | topic |  web
    Route::get('/post/add')->controller('Post\AddPostController')->name('post.add');
    Route::get('/topic/add')->controller('Facets\AddFacetController', ['topic'])->name('topics.add');
    Route::get('/blog/add')->controller('Facets\AddFacetController', ['blog'])->name('blogs.add');
    Route::get('/web/add')->controller('Item\AddWebController')->name('web.add');
    
    Route::get('/post/edit/{id}')->controller('Post\EditPostController')->where(['id' => '[0-9]+'])->name('post.edit');
    Route::get('/answer/edit/{id}')->controller('Answer\EditAnswerController')->where(['id' => '[0-9]+'])->name('answer.edit');
    Route::get('/topic/edit/{id}')->controller('Facets\EditFacetController')->where(['id' => '[0-9]+'])->name('topic.edit');
    Route::get('/web/edit/{id}')->controller('Item\EditWebController')->where(['id' => '[0-9]+'])->name('web.edit');
    Route::get('/blog/edit/{id}')->controller('Facets\EditFacetController')->where(['id' => '[0-9]+'])->name('blog.edit');
    
    // Adding a post from a topic
    Route::get('/post/add/{topic_id}')->controller('Post\AddPostController')->where(['topic_id' => '[0-9]+']);

    Route::get('/u/{login}/setting')->controller('User\SettingController@settingForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting'); 
    Route::get('/u/{login}/setting/avatar')->controller('User\SettingController@avatarForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.avatar');
    Route::get('/u/{login}/setting/security')->controller('User\SettingController@securityForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.security');
    Route::get('/u/{login}/setting/notifications')->controller('User\SettingController@notificationsForm')->where(['login' => '[A-Za-z0-9]+'])->name('setting.notifications');

    Route::get('/post/img/{id}/remove')->controller('Post\EditPostController@imgPostRemove')->where(['id' => '[0-9]+']);
    Route::get('/u/{login}/delete/cover')->controller('User\SettingController@coverRemove')->where(['login' => '[A-Za-z0-9]+']); 

    Route::get('/logout')->controller('Auth\LogoutController')->name('logout');

    Route::get('/u/{login}/messages')->controller('MessagesController')->where(['login' => '[A-Za-z0-9]+'])->name('user.messages');   
    Route::get('/messages/read/{id}')->controller('MessagesController@dialog')->where(['id' => '[0-9]+'])->name('user.dialogues'); 
    Route::get('/u/{login}/mess')->controller('MessagesController@messages')->where(['login' => '[A-Za-z0-9]+'])->name('user.send.messages'); 

	Route::get('/u/{login}/notifications')->controller('NotificationsController')->where(['login' => '[A-Za-z0-9]+'])->name('user.notifications'); 
    Route::get('/notifications/read/{id}')->controller('NotificationsController@read')->where(['id' => '[0-9]+'])->name('notif.read');  
    Route::get('/notifications/delete')->controller('NotificationsController@remove')->name('notif.remove');  
    
    Route::get('/u/{login}/favorites')->controller('User\UserController@favorites')->where(['login' => '[A-Za-z0-9]+'])->name('user.favorites');
    Route::get('/u/{login}/subscribed')->controller('User\UserController@subscribed')->where(['login' => '[A-Za-z0-9]+'])->name('user.subscribed');

    Route::get('/u/{login}/drafts')->controller('User\UserController@drafts')->where(['login' => '[A-Za-z0-9]+'])->name('user.drafts');
    
    Route::get('/u/{login}/invitations')->controller('User\InvitationsController@invitationForm')->where(['login' => '[A-Za-z0-9]+'])->name('user.invitations');

    Route::get('/topics/my')->controller('Facets\AllFacetController', ['topics.my', 'topics'])->name('topics.my');
    Route::get('/topics/my/page/{page?}')->controller('Facets\AllFacetController', ['topics.my', 'topics'])->where(['page' => '[0-9]+']); 
 
    Route::get('/blogs/my')->controller('Facets\AllFacetController', ['blogs.my', 'blogs'])->name('blogs.my');
 
    Route::get('/all')->controller('HomeController', ['all'])->name('main.all');
    Route::get('/all/page/{page?}')->controller('HomeController', ['all'])->where(['page' => '[0-9]+']);
    
    Route::get('/moderations')->controller('ActionController@moderation')->name('moderation');
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

    Route::get('/invite')->controller('User\InvitationsController@inviteForm')->name('invite');
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
    // Viewing a post in the feed
    Route::get('/post/shown')->controller('Post\PostController@shownPost');
    Route::get('/user/card')->controller('User\UserController@card');
    Route::get('/msg/go')->controller('Post\AddPostController@msg');
    // Calling the Comment form
    Route::get('/comments/addform')->controller('Comment\AddCommentController');
Route::endType();
  
Route::get('/topic/{slug}/followers/{id}')->controller('Facets\TopicFacetController@followers')->where(['slug' => '[a-z0-9-]+', 'id' => '[0-9]+'])->name('topic.followers');  
  
// Other pages without authorization
Route::get('/post/{id}')->controller('Post\PostController')->where(['id' => '[0-9]+']);
Route::get('/post/{id}/{slug}')->controller('Post\PostController')->where(['id' => '[0-9]+', 'slug' => '[A-Za-z0-9-_]+'])->name('post');

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

Route::get('/topics')->controller('Facets\AllFacetController', ['topics.all', 'topics'])->name('topics.all');
Route::get('/topics/page/{page?}')->controller('Facets\AllFacetController', ['topics.all', 'topics'])->where(['page' => '[0-9]+']);
Route::get('/topics/new')->controller('Facets\AllFacetController', ['topics.new', 'topics'])->name('topics.new');
Route::get('/topics/new/page/{page?}')->controller('Facets\AllFacetController', ['topics.new', 'topics'])->where(['page' => '[0-9]+']);

Route::get('/topic/{slug}')->controller('Facets\TopicFacetController', ['feed'])->where(['slug' => '[a-zA-Z0-9-]+'])->name('topic');
Route::get('/topics/structure')->controller('Facets\TreeFacetController')->name('topic.structure');
Route::get('/topic/{slug}/recommend')->controller('Facets\TopicFacetController', ['recommend'])->where(['slug' => '[a-z0-9-]+'])->name('recommend');
Route::get('/topic/{slug}/recommend/page/{page?}')->controller('Facets\TopicFacetController', ['recommend'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/page/{page?}')->controller('Facets\TopicFacetController', ['feed'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);
Route::get('/topic/{slug}/info')->controller('Facets\TopicFacetController@info')->where(['slug' => '[a-z0-9-]+'])->name('topic.info');

Route::get('/blogs')->controller('Facets\AllFacetController', ['blogs.all', 'blogs'])->name('blogs.all');
Route::get('/blogs/page/{page?}')->controller('Facets\AllFacetController', ['topics.all', 'blogs'])->where(['page' => '[0-9]+']);
Route::get('/blogs/new')->controller('Facets\AllFacetController', ['blogs.new', 'blogs'])->name('blogs.new');
Route::get('/blogs/new/page/{page?}')->controller('Facets\AllFacetController', ['blogs.new', 'blogs'])->where(['page' => '[0-9]+']);

Route::get('/blog/{slug}')->controller('Facets\BlogFacetController', ['feed'])->where(['slug' => '[a-zA-Z0-9-]+'])->name('blog');
Route::get('/blog/{slug}/page/{page?}')->controller('Facets\BlogFacetController', ['feed'])->where(['slug' => '[a-z0-9-]+', 'page' => '[0-9]+']);

Route::get('/web')->controller('Item\WebController', ['all'])->name('web');
Route::get('/web/page/{page?}')->controller('Item\WebController', ['all'])->where(['page' => '[0-9]+']);

Route::get('/domain/{domain}')->controller('Item\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+'])->name('domain');
Route::get('/domain/{domain}/page/{page?}')->controller('Item\WebController@posts', ['feed'])->where(['domain' => '[A-Za-z0-9-.]+', 'page' => '[0-9]+']);

Route::get('/web/{slug}')->controller('Item\WebController@sites', ['all'])->where(['slug' => '[A-Za-z0-9-]+'])->name('web.topic');
Route::get('/web/{slug}/new')->controller('Item\WebController@sites', ['new'])->where(['slug' => '[A-Za-z0-9-]+'])->name('web.topic.new');

Route::get('/web/website/{slug}')->controller('Item\WebController@website', ['feed'])->where(['slug' => '[A-Za-z0-9.-]+'])->name('web.website');
Route::get('/web/{slug}/page/{page?}')->controller('Item\WebController@sites', ['feed'])->where(['slug' => '[A-Za-z0-9-]+', 'page' => '[0-9]+']);

Route::get('/')->controller('HomeController', ['feed']);
Route::get('/page/{page?}')->controller('HomeController', ['feed'])->where(['page' => '[0-9]+']);
Route::get('/top')->controller('HomeController', ['top'])->name('main.top');
Route::get('/top/page/{page?}')->controller('HomeController', ['top'])->where(['page' => '[0-9]+']);

Route::get('/sitemap.xml')->controller('RssController');
Route::get('/turbo-feed/topic/{slug}')->controller('RssController@turboFeed')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/rss-feed/topic/{slug}')->controller('RssController@rssFeed')->where(['slug' => '[A-Za-z0-9-]+']);

Route::type(['get', 'post'])->get('/search')->controller('SearchController')->name('search');

require 'admin.php';