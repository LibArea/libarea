<?php
/*
 * Site Directory Configuration
 * Конфигурация каталог сайтов
 */

return [

	'name' => 'Каталог',
	'url' => 'http://www.ru',

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
	],


	/*
    |--------------------------------------------------------------------------
    | Initial limits
    |--------------------------------------------------------------------------
    |
    | Initial limits for content creation in 1 day
    | Начальные лимиты на создание контента за 1 день
    |
    */

	'tl_add_item'	=> 2,
    'tl_add_reply'	=> 2,

	/*
    |--------------------------------------------------------------------------
    | Initial limits
    |--------------------------------------------------------------------------
    |
    | Initial limits for content creation in 1 day
    | Начальные лимиты на создание контента за 1 день
    |
    */

	'perDay_item'	=> 1,
    'perDay_reply'	=> 3,

	/*
    |--------------------------------------------------------------------------
    | Edit time
    |--------------------------------------------------------------------------
    |
    | How long can an author edit their content (30 - minutes, 0 - always)
    | Сколько времени автор может редактировать свой контент (30 - минут, 0 - всегда)
    |
    */

	'edit_time_item'	=> 0,
    'edit_time_reply'   => 0,

	/*
    |--------------------------------------------------------------------------
    | Odds for limits
    |--------------------------------------------------------------------------
    |
    | Odds for limits depending on the level of trust for 1 day
    | Коэффициенты на лимиты в зависимости от уровня доверия на 1 день
    |
    */

	'multiplier_1'       => 1,
	'multiplier_2'       => 2,
	'multiplier_3'       => 3,
	'multiplier_4'       => 4,

	/*
    |--------------------------------------------------------------------------
    | Screenshots
    |--------------------------------------------------------------------------
    |
    | Screenshots of the service https://screenshotone.com/
    | Скриншоты сервиса https://screenshotone.com/
    |
    */

	'sc_access_key'         => '***',
	'sc_secret_key'         => '***',
];
