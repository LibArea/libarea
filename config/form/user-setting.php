<?php
/*
 * Profile Fields (Contacts)
 * Поля профиля (Контакты)
 */

return [
    [
        'url'       => 'website',
        'addition'  => false,
        'title'     => 'website',
        'lang'      => __('URL'),
        'help'      => 'https://site.ru',
        'name'      => 'website'
    ], [
        'url'       => false,
        'addition'  => false,
        'title'     => 'location',
        'lang'      => __('city'),
        'help'      => __('for.example') . ': Moscow',
        'name'      => 'location'
    ], [
        'url'       => 'public_email',
        'addition'  => 'mailto:',
        'title'     => 'public_email',
        'lang'      => __('E-mail'),
        'help'      => '**@**.ru',
        'name'      => 'public_email'
    ], [
        'url'       => 'skype',
        'addition'  => 'skype:',
        'title'     => 'skype',
        'lang'      => __('Skype'),
        'help'      => 'skype:<b>NICK</b>',
        'name'      => 'skype'
    ], [
        'url'       => 'telegram',
        'addition'  => 'tg://resolve?domain=',
        'title'     => 'telegram',
        'lang'      => __('Telegram'),
        'help'      => 'tg://resolve?domain=<b>NICK</b>',
        'name'      => 'telegram'
    ], [
        'url'       => 'vk',
        'addition'  => 'https://vk.com/',
        'title'     => 'vk',
        'lang'      => __('VK'),
        'help'      => 'https://vk.com/<b>NICK / id</b>',
        'name'      => 'vk'
    ],
];
