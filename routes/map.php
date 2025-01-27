<?php

use App\Middlewares\{DefaultMiddleware, LimitsMiddleware};

use App\Bootstrap\Services\Auth\RegType;
use App\Meta\OgImage;

use App\Controllers\{
	HomeController,
	FormController,
	AuditController,
	ActionController,
	MessagesController,
	DeviceController,
	NotificationController,
	VotesController,
	FavoriteController,
	SubscriptionController,
	FolderController,
	IgnoredController,
	RssController,
	Post\PostController,
	Post\EditPostController,
	Post\AddPostController,
	Comment\CommentController,
	Comment\AddCommentController,
	Comment\EditCommentController,
	Comment\CommentBestController,
	Auth\LoginController,
	Auth\LogoutController,
	Auth\RecoverController,
	Auth\RegisterController,
	User\SettingController,
	User\UserController,
	User\InvitationsController,
	User\ProfileController,
	Facet\FacetController,
	Facet\TopicFacetController,
	Facet\BlogFacetController,
	Facet\AddFacetController,
	Facet\EditFacetController,
	Facet\RedirectController,
	Facet\TeamFacetController,
	Facet\ReadController,
	Poll\PollController,
	Poll\AddPollController,
	Poll\EditPollController,
};

/**
 * Navigation of the central page of the site
 * Навигация центральной странице сайта
 */
Route::get('/')->controller(HomeController::class, 'feed')->name('home');
Route::get('/questions')->controller(HomeController::class, 'questions')->name('main.questions');
Route::get('/posts')->controller(HomeController::class, 'posts')->name('main.posts');
Route::get('/all')->controller(HomeController::class, 'all')->name('main.all');

Route::get('/blogs')->controller(FacetController::class, 'blogAll')->name('blogs.all');
Route::get('/blogs/new')->controller(FacetController::class, 'blogNew')->name('blogs.new');
Route::get('/topics')->controller(FacetController::class, 'topicAll')->name('topics.all');
Route::get('/topics/new')->controller(FacetController::class, 'topicNew')->name('topics.new');

Route::get('/users')->controller(UserController::class, 'all')->name('users.all');
Route::get('/users/new')->controller(UserController::class, 'new')->name('users.new');

Route::get('/comments')->controller(CommentController::class, 'all')->name('comments');

/**
 * Access after authorization (TL1)
 * Доступ после авторизации (TL1)
 * ->middleware(Registrar::class, data: [RegType::UNDEFINED_USER, '>=']);
 */
Route::toGroup()->middleware(DefaultMiddleware::class, data: [RegType::USER_FIRST_LEVEL, '>=']);

    Route::post('/backend/upload/{type}/{id}')->controller(EditPostController::class, 'uploadContentImage')->where(['type' => '[a-z-]+', 'id' => '[0-9]+']);

	Route::get('/logout')->controller(LogoutController::class, 'logout')->name('logout');
	Route::get('/favorites')->controller(UserController::class, 'favorites')->name('favorites');
	Route::post('/search/select/{type}')->controller(FormController::class)->where(['type' => '[a-z]+']);
	Route::post('/post/grabtitle')->controller(AddPostController::class, 'grabMeta');
	Route::post('/status/action')->controller(ActionController::class, 'deletingAndRestoring');

	Route::get('/blogs/my')->controller(FacetController::class, 'blogMy')->name('blogs.my');
	Route::get('/topics/my')->controller(FacetController::class, 'topicMy')->name('topics.my');
	Route::get('/post/scroll/{type}')->controller(HomeController::class, 'scroll'); 

	/**
	 * Participant Settings
	 * Настройки участника
	 */
	Route::get('/setting/')->controller(SettingController::class)->name('setting');
	Route::get('/setting/avatar')->controller(SettingController::class, 'avatarForm')->where(['type' => '[a-z_]+'])->name('setting.avatar');
	Route::get('/setting/ignored')->controller(SettingController::class, 'ignoredForm')->where(['type' => '[a-z_]+'])->name('setting.ignored');
	Route::get('/setting/security')->controller(SettingController::class, 'securityForm')->where(['type' => '[a-z_]+'])->name('setting.security');
	Route::get('/setting/notifications')->controller(SettingController::class, 'notificationForm')->where(['type' => '[a-z_]+'])->name('setting.notification');
	Route::get('/setting/preferences')->controller(SettingController::class, 'preferencesForm')->where(['type' => '[a-z_]+'])->name('setting.preferences');
	Route::get('/setting/notifications')->controller(SettingController::class, 'deletionForm')->where(['type' => '[a-z_]+'])->name('setting.deletion');

	Route::get('/messages')->controller(MessagesController::class)->name('messages');
    Route::get('/messages/{id}')->controller(MessagesController::class, 'dialog')->where(['id' => '[0-9]+'])->name('dialogues'); 
    Route::get('/messages/@{login}')->controller(MessagesController::class, 'messages')->where(['login' => '[A-Za-z0-9-]+'])->name('send.messages');

	Route::get('/notifications')->controller(NotificationController::class)->name('notifications');
	Route::get('/notification/{id}')->controller(NotificationController::class, 'read')->where(['id' => '[0-9]+'])->name('notif.read');  
    Route::get('/notifications/delete')->controller(NotificationController::class, 'remove')->name('notif.remove');  
	
	Route::get('/invitations')->controller(InvitationsController::class, 'invitationForm')->name('invitations');
	Route::get('/read')->controller(UserController::class, 'read')->name('read');
	Route::get('/drafts')->controller(UserController::class, 'drafts')->name('drafts');
	Route::get('/polls')->controller(PollController::class)->name('polls');
    Route::get('/poll/{id}')->controller(PollController::class, 'poll')->where(['id' => '[0-9]+'])->name('poll');
	Route::get('/subscribed')->controller(UserController::class, 'subscribed')->name('subscribed');
	
    Route::get('/favorites/folders')->controller(UserController::class, 'folders')->name('favorites.folders');
    Route::get('/favorites/folders/{id}')->controller(UserController::class, 'foldersFavorite')->where(['id' => '[0-9]+'])->name('favorites.folder.id');
	
	// Формы добавления контента
	Route::get('/add/post')->controller(AddPostController::class)->name('post.form.add');
	Route::get('/add/post/{facet_id}')->controller(AddPostController::class)->where(['facet_id' => '[0-9]+']);
	Route::get('/add/poll')->controller(AddPollController::class)->name('poll.form.add');
	Route::get('/add/facet/{type}')->controller(AddFacetController::class)->where(['type' => '[a-z]+'])->name('facet.form.add');

    // Формы изменение контента
	Route::get('/edit/post/{id}')->controller(EditPostController::class)->where(['id' => '[0-9]+'])->name('post.form.edit');
	Route::get('/edit/page/{id}')->controller(EditPostController::class)->where(['id' => '[0-9]+'])->name('page.form.edit');
    Route::get('/edit/comment/{id}')->controller(EditCommentController::class)->where(['id' => '[0-9]+'])->name('comment.form.edit'); 
	Route::get('/edit/poll/{id}')->controller(EditPollController::class)->where(['id' => '[0-9]+'])->name('poll.form.edit'); 
	Route::get('/edit/facet/{type}/{id}')->controller(EditFacetController::class)->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('facet.form.edit'); 
	Route::get('/edit/facet/logo/{type}/{id}')->controller(EditFacetController::class, 'logoForm')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('facet.form.logo.edit'); 
	Route::get('/team/edit/{type}/{id}')->controller(TeamFacetController::class)->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('team.form.edit');
	
	Route::get('/redirect/facet/{id}')->controller(RedirectController::class)->where(['id' => '[0-9]+'])->name('redirect.facet');
	
	Route::get('/post/img/{id}/remove')->controller(EditPostController::class, 'coverPostRemove')->where(['id' => '[0-9]+'])->name('delete.post.cover');
	Route::get('/cover/img/{id}/remove')->controller(SettingController::class, 'coverUserRemove')->where(['id' => '[0-9]+'])->name('delete.user.cover');

    Route::toGroup()->protect();
		Route::post('/favorite')->controller(FavoriteController::class);
		Route::post('/votes')->controller(VotesController::class);
		Route::post('/flag/repost')->controller(AuditController::class); 
		Route::post('/post/profile')->controller(PostController::class, 'postProfile'); 
		Route::post('/post/recommend')->controller(AddPostController::class, 'recommend'); 

		Route::post('/folder/content/del')->controller(FolderController::class, 'delFolderContent');
		Route::post('/folder/del')->controller(FolderController::class, 'delFolder');
		Route::post('/folder/content/save')->controller(FolderController::class, 'addFolderContent');
		
	
		Route::post('/focus')->controller(SubscriptionController::class);
		
		Route::post('/post/recommend')->controller(AddPostController::class, 'recommend'); 
		
		Route::post('/ignored')->controller(IgnoredController::class);
		Route::post('/best')->controller(CommentBestController::class);
		
		Route::post('/poll/option/del')->controller(EditPollController::class);
		Route::post('/poll')->controller(PollController::class, 'vote');
		Route::post('/new/email')->controller(SettingController::class, 'newEmail');
	Route::endGroup();	

    Route::post('/notif')->controller(NotificationController::class, 'get');
    Route::post('/device')->controller(DeviceController::class, 'set');
	
    Route::toGroup()->middleware(LimitsMiddleware::class)->protect();
	
		// Отправка и изменение контента
		Route::post('/user/edit/profile')->controller(SettingController::class, 'profile')->where(['type' => '[a-z]+'])->name('setting.edit.profile');
		Route::post('/user/edit/avatar')->controller(SettingController::class, 'avatar')->where(['type' => '[a-z]+'])->name('setting.edit.avatar');
		Route::post('/user/edit/security')->controller(SettingController::class, 'security')->where(['type' => '[a-z]+'])->name('setting.edit.security');
		Route::post('/user/edit/preferences')->controller(SettingController::class, 'preferences')->where(['type' => '[a-z]+'])->name('setting.edit.preferences');
		Route::post('/user/edit/notification')->controller(SettingController::class, 'notification')->where(['type' => '[a-z]+'])->name('setting.edit.notification');

		Route::post('/team/edit/{type}/{id}')->controller(TeamFacetController::class, 'edit')->where(['type' => '[a-z]+', 'id' => '[0-9]+'])->name('team.edit');
		Route::post('/edit/content/{type}')->controller(EditPostController::class, 'edit')->where(['type' => '[a-z]+'])->name('edit.post');
		Route::post('/edit/comment')->controller(EditCommentController::class, 'edit')->name('edit.comment');
		Route::post('/edit/facet/{type}')->controller(EditFacetController::class, 'edit')->where(['type' => '[a-z]+'])->name('edit.facet');
		Route::post('/edit/facet/logo/{type}/{facet_id}')->controller(EditFacetController::class, 'logoEdit')->where(['type' => '[a-z]+', 'facet_id' => '[0-9]+'])->name('edit.logo.facet');
		Route::post('/edit/poll')->controller(EditPollController::class, 'edit')->name('edit.poll');
		Route::post('/edit/message')->controller(MessagesController::class, 'edit')->name('edit.message');

		// Отправка и добавление контента
		Route::post('/add/folder')->controller(FolderController::class, 'add')->name('add.folder');
		Route::post('/add/content/{type}')->controller(AddPostController::class, 'add')->where(['type' => '[a-z]+'])->name('add.post');
		Route::post('/add/comment')->controller(AddCommentController::class, 'add')->name('add.comment');
		Route::post('/add/facet/{type}')->controller(AddFacetController::class, 'add')->where(['type' => '[a-z]+'])->name('add.facet');
		Route::post('/add/poll')->controller(AddPollController::class, 'add')->name('add.poll');
		Route::post('/add/message')->controller(MessagesController::class, 'add')->name('add.message');
		Route::post('/add/invitation')->controller(InvitationsController::class, 'add')->name('add.invitation');
		
    Route::endGroup();    
Route::endGroup();

/**
 * Access without authorization (TL0)
 * Доступ без авторизации (TL0)
 */
Route::toGroup()->middleware(DefaultMiddleware::class, data: [RegType::USER_ZERO_LEVEL, '=']);
    Route::toGroup()->protect();
        Route::post('/recover/send')->controller(RecoverController::class)->name('recover.send'); 
        Route::post('/recover/send/pass')->controller(RecoverController::class, 'remindNew')->name('new.pass'); 
        Route::post('/register/add')->controller(RegisterController::class)->name('register.add');
		Route::post('/login')->controller(LoginController::class)->name('authorization');
    Route::endGroup();

    Route::get('/invite')->controller(InvitationsController::class, 'inviteForm')->name('invite');
	Route::get('/register')->controller(RegisterController::class, 'showRegisterForm')->name('register');
    Route::get('/register/invite/{code}')->controller(RegisterController::class, 'showInviteForm')->where(['code' => '[a-z0-9-]+'])->name('invite.reg');
    Route::get('/recover')->controller(RecoverController::class, 'showPasswordForm')->name('recover');  
    Route::get('/recover/remind/{code}')->controller(RecoverController::class, 'showRemindForm')->where(['code' => '[A-Za-z0-9-]+'])->name('recover.code');
    Route::get('/email/activate/{code}')->controller(RecoverController::class, 'ActivateEmail')->where(['code' => '[A-Za-z0-9-]+'])->name('activate.code'); 
    Route::get('/login')->controller(LoginController::class, 'showLoginForm')->name('login');
Route::endGroup();

Route::toGroup()->protect();
    Route::post('/activatingform/addcomment')->controller(AddCommentController::class);
	Route::post('/activatingform/editmessage')->controller(MessagesController::class, 'addForma');
	Route::post('/activatingnatifpopup')->controller(NotificationController::class, 'addForma'); 
Route::endGroup();	

Route::get('/domain/{domain}')->controller(PostController::class, 'domain')->where(['domain' => '[a-z0-9-.]+'])->name('domain');

// Other pages without authorization
Route::get('/post/{id}')->controller(PostController::class, 'post')->where(['id' => '[0-9]+'])->name('post.id');
Route::get('/post/{id}/{slug}')->controller(PostController::class, 'post')->where(['id' => '[0-9]+', 'slug' => '[A-Za-z0-9-_]+'])->name('post');

// Страницы info
Route::get('/{facet_slug}/article/{slug}')->controller(PostController::class, 'page')->where(['facet_slug' => '[A-Za-z0-9-_]+', 'slug' => '[A-Za-z0-9-_]+'])->name('facet.article'); 

Route::get('/@{login}')->controller(ProfileController::class)->where(['login' => '[A-Za-z0-9-]+'])->name('profile');
Route::get('/@{login}/posts')->controller(ProfileController::class, 'posts')->where(['login' => '[A-Za-z0-9-]+'])->name('profile.posts');
Route::get('/@{login}/comments')->controller(ProfileController::class, 'comments')->where(['login' => '[A-Za-z0-9-]+'])->name('profile.comments');

Route::get('/topic/{slug}/recommend')->controller(TopicFacetController::class, 'recommend')->where(['slug' => '[a-z0-9-]+'])->name('topic.recommend');
Route::get('/topic/{slug}/questions')->controller(TopicFacetController::class, 'questions')->where(['slug' => '[a-z0-9-]+'])->name('topic.questions');
Route::get('/topic/{slug}/top')->controller(TopicFacetController::class, 'top')->where(['slug' => '[a-z0-9-]+'])->name('topic.top');
Route::get('/topic/{slug}/posts')->controller(TopicFacetController::class, 'posts')->where(['slug' => '[a-z0-9-]+'])->name('topic.posts');
Route::get('/topic/{slug}/info')->controller(TopicFacetController::class, 'info')->where(['slug' => '[a-z0-9-]+'])->name('topic.info');
Route::get('/topic/{slug}/writers')->controller(TopicFacetController::class, 'writers')->where(['slug' => '[a-z0-9-]+'])->name('topic.writers');
Route::get('/topic/{slug}')->controller(TopicFacetController::class, 'feed')->where(['slug' => '[a-z0-9-]+'])->name('topic');

Route::get('/blog/{slug}/questions')->controller(BlogFacetController::class, 'questions')->where(['slug' => '[a-z0-9-]+'])->name('blog.questions');
Route::get('/blog/{slug}/posts')->controller(BlogFacetController::class, 'posts')->where(['slug' => '[a-z0-9-]+'])->name('blog.posts');
Route::get('/blog/{slug}/read')->controller(ReadController::class)->where(['slug' => '[a-z0-9-]+'])->name('blog.read');
Route::get('/blog/{slug}')->controller(BlogFacetController::class, 'feed')->where(['slug' => '[a-z0-9-]+'])->name('blog');

Route::get('/sitemap.xml')->controller(RssController::class);
Route::get('/rss/all/posts')->controller(RssController::class, 'postsAll');
Route::get('/turbo-feed/topic/{slug}')->controller(RssController::class, 'turboFeed')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/rss-feed/topic/{slug}')->controller(RssController::class, 'rssFeed')->where(['slug' => '[A-Za-z0-9-]+']);
Route::get('/og-image/{id}')->controller(PostController::class, 'OgImage')->where(['id' => '[0-9-]+'])->name('og.image');