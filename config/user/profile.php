<?php
/*
 * Showing fields on the profile page
 * Показ полей на странице профиля
 */

return [
	'sidebar' => [
		[
			'url'       => 'website',
			'addition'  => false,
			'title'     => 'website',
			'lang'      => 'app.url',
		], [
			'url'       => false,
			'addition'  => false,
			'title'     => 'location',
			'lang'      => 'app.city',
		], [
			'url'       => 'public_email',
			'addition'  => 'mailto:',
			'title'     => 'public_email',
			'lang'      => 'app.email',
		], [
			'url'       => 'github',
			'addition'  => false,
			'title'     => 'github',
			'lang'      => 'app.github',
		], [
			'url'       => 'skype',
			'addition'  => 'skype:',
			'title'     => 'skype',
			'lang'      => 'app.skype',
		], [
			'url'       => 'telegram',
			'addition'  => false,
			'title'     => 'telegram',
			'lang'      => 'app.telegram',
		], [
			'url'       => 'vk',
			'addition'  => false,
			'title'     => 'vk',
			'lang'      => 'app.vk',
		],
	],
];
