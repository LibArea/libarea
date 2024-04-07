<?php
/*
 * Sites in the directory
 * Сайты в каталоге
 */

return [
	// Site characteristics. Used for grouping.
	// Характеристика сайтов. Используется для группировки.
	'type' => ['github', 'blog', 'forum', 'portal', 'reference', 'goods'],

	'categories' => [
		/* [
			'title' => 'web.hi_tech',
			'url'   => 'hi-tech',
			'sub'   => [
					[
						'title' => 'web.software',
						'url'   => 'software',
						'sub'   => '',
					],  
					[
						'title' => 'web.internet',
						'url'   => 'internet',
						'sub'   => '',
					],  
					[
						'title' => 'web.cms',
						'url'   => 'content-management-system',
						'sub'   => '',
					],  
			
			],
		], */
		[
			'title' => 'web.reference_info',
			'url'   => 'reference',
			'help'  => 'web.reference_help',
			'sub'   => [],

		], [
			'title' => 'web.internet',
			'url'   => 'internet',
			'sub'   => '',
		],
		/* [
			'title' => 'web.news',
			'url'   => 'media',
			'sub'   => [],
		], [
			'title' => 'web.life',
			'url'   => 'private-life',
			'help'  => 'web.life_help',
			'sub'   => [],
		], [
			'title' => 'web.science',
			'url'   => 'science',
			'sub'   => [],
		], [
			'title' => 'web.business',
			'url'   => 'business',
			'sub'   => [],
		], [
			'title' => 'web.culture',
			'url'   => 'culture-arts',
			'sub'   => [],
		],  [
			'title' => 'web.society',
			'url'   => 'society',
			'sub'   => [],
		], */
	]
];
