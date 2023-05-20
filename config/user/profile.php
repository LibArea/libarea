<?php
/*
 * Показ полей на странице профиля
 * Показ полей на странице профиля
 */

return [
    [
        'url'       => 'website',
        'addition'  => false,
        'title'     => 'website',
        'lang'      => __('app.url'),
    ], [
        'url'       => false,
        'addition'  => false,
        'title'     => 'location',
        'lang'      => __('app.city'),
    ], [
        'url'       => 'public_email',
        'addition'  => 'mailto:',
        'title'     => 'public_email',
        'lang'      => 'Email',
    ], [
        'url'       => 'github',
        'addition'  => false,
        'title'     => 'github',
        'lang'      => 'GitHub',
    ], [
        'url'       => 'skype',
        'addition'  => 'skype:',
        'title'     => 'skype',
        'lang'      => 'Skype',
    ], [
        'url'       => 'telegram',
        'addition'  => 'https://t.me/',
        'title'     => 'telegram',
        'lang'      => 'Telegram',
    ], [
        'url'       => 'vk',
        'addition'  => 'https://vk.com/',
        'title'     => 'vk',
        'lang'      => 'Vk',
    ],
];
