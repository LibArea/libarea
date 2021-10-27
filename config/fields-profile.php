<?php
/*
 * Поля профиля (Контакты)
 * Profile Fields (Contacts)
 */

return [
    [
        'url'       => 'user_website',
        'addition'  => false,
        'title'     => 'user_website',
        'lang'      => Translate::get('URL'),
        'help'      => 'https://site.ru',
        'name'      => 'website'
    ], [
        'url'       => false,
        'addition'  => false,
        'title'     => 'user_location',
        'lang'      => Translate::get('city'),
        'help'      => Translate::get('for example') . ': Moscow',
        'name'      => 'location'
    ], [
        'url'       => 'user_public_email',
        'addition'  => 'mailto:',
        'title'     => 'user_public_email',
        'lang'      => Translate::get('E-mail'),
        'help'      => '**@**.ru',
        'name'      => 'public_email'
    ], [
        'url'       => 'user_skype',
        'addition'  => 'skype:',
        'title'     => 'user_skype',
        'lang'      => Translate::get('Skype'),
        'help'      => 'skype:<b>NICK</b>',
        'name'      => 'skype'
    ], [
        'url'       => 'user_twitter',
        'addition'  => 'https://twitter.com/',
        'title'     => 'user_twitter',
        'lang'      => Translate::get('Twitter'),
        'help'      => 'https://twitter.com/<b>NICK</b>',
        'name'      => 'twitter'
    ], [
        'url'       => 'user_telegram',
        'addition'  => 'tg://resolve?domain=',
        'title'     => 'user_telegram',
        'lang'      => Translate::get('Telegram'),
        'help'      => 'tg://resolve?domain=<b>NICK</b>',
        'name'      => 'telegram'
    ], [
        'url'       => 'user_vk',
        'addition'  => 'https://vk.com/',
        'title'     => 'user_vk',
        'lang'      => Translate::get('VK'),
        'help'      => 'https://vk.com/<b>NICK / id</b>',
        'name'      => 'vk'
    ],
];
