<?= insert(
	'/_block/navigation/nav',
	[
		'list' =>  [
			[
				'id'	=> 'facet.feed',
				'url'	=> url('topic', ['slug' => $slug]),
				'title'	=> 'app.feed',
			], [
				'id'    => 'facet.posts',
				'url'   => url('topic.posts', ['slug' => $slug]),
				'title' => 'app.posts',
			], [
				'id'    => 'facet.questions',
				'url'   => url('topic.questions', ['slug' => $slug]),
				'title'	=> 'app.questions',
			], [
				'id'	=> 'facet.recommend',
				'url'	=> url('topic.recommend', ['slug' => $slug]),
				'title'	=> 'app.recommended',
			], [
				'id'	=> 'writers',
				'url'	=> url('topic.writers', ['slug' => $slug]),
				'title'	=> 'app.writers',
			]
		]
	]
); ?>