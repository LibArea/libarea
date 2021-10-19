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
        'lang'      => lang('URL'),
        'help'      => 'https://site.ru',
        'name'      => 'website'
    ], [
        'url'       => false,
        'addition'  => false,
        'title'     => 'user_location',
        'lang'      => lang('city'),
        'help'      => 'Moscow...',
        'name'      => 'location'
    ], [
        'url'       => 'user_public_email',
        'addition'  => 'mailto:',
        'title'     => 'user_public_email',
        'lang'      => lang('E-mail'),
        'help'      => '**@**.ru',
        'name'      => 'public_email'
    ], [
        'url'       => 'user_skype',
        'addition'  => 'skype:',
        'title'     => 'user_skype',
        'lang'      => lang('Skype'),
        'help'      => 'skype:<b>NICK</b>',
        'name'      => 'skype'
    ], [
        'url'       => 'user_twitter',
        'addition'  => 'https://twitter.com/',
        'title'     => 'user_twitter',
        'lang'      => lang('Twitter'),
        'help'      => 'https://twitter.com/<b>NICK</b>',
        'name'      => 'twitter'
    ], [
        'url'       => 'user_telegram',
        'addition'  => 'tg://resolve?domain=',
        'title'     => 'user_telegram',
        'lang'      => lang('Telegram'),
        'help'      => 'tg://resolve?domain=<b>NICK</b>',
        'name'      => 'telegram'
    ], [
        'url'       => 'user_vk',
        'addition'  => 'https://vk.com/',
        'title'     => 'user_vk',
        'lang'      => lang('VK'),
        'help'      => 'https://vk.com/<b>NICK / id</b>',
        'name'      => 'vk'
    ],
];
