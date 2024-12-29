<?= insert(
	'/_block/navigation/menu',
	[
		'menu' => [
			[
				'url'   => url('home'),
				'title' => 'app.feed',
				'icon'  => 'monitor',
				'id'    => 'main',
			], [
				'url'   => url('topics.all'),
				'title' => 'app.topics',
				'icon'  => 'hash',
				'id'    => 'topics',
			], [
				'url'   => url('blogs.all'),
				'title' => 'app.blogs',
				'icon'  => 'book',
				'id'    => 'blogs',
			], [
				'url'   => url('users.all'),
				'title' => 'app.users',
				'icon'  => 'users',
				'id'    => 'all_users',
			], [
				'url'   => url('comments'),
				'title' => 'app.comments',
				'icon'  => 'comments',
				'id'    => 'all_comments',
			],

			/* Вы можете закомментировать эту секцию */
			[
				'url'   => url('web'),
				'title' => 'app.catalog',
				'icon'  => 'link',
				'id'    => 'catalog',
			],
			/* end */

			[
				'url'   => url('search'),
				'title' => 'app.search',
				'icon'  => 'search',
				'id'    => 'search',
				'css'   => 'none mb-block',
			], [
				'hr'    => true,
			], [
				'tl'    => 1,
				'url'   => url('favorites'),
				'title' => 'app.favorites',
				'icon'  => 'bookmark',
				'id'    => 'favorites',
			], [
				'tl'    => 10,
				'url'   => url('admin.users'),
				'title' => 'app.admin',
				'icon'  => 'users',
				'id'    => 'admin',
			],
		],

		'type' => $type,
		'topics_user' => $topics_user,
	]
);
?>