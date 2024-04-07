<?php
/*
 * Setting up an activity feed
 * Настройка ленты
 */

return [
    // How many likes should a post get to be visible on the central page of the site
    // Сколько лайков должен набрать пост, чтобы был виден на центральной странице сайта
    'countLike' => 1,

    // Appearance of the post in the feed: classic or card. If true, then classic (minimum)
    // Внешний вид поста в ленте: classic или card. Если true, то classic (минимальный)
    'classic'   => false,

    // Does the site use NSFW content? Enable for post and in profile settings.
    // Сайт использует nsfw NSFW контент? Включить для поста и в настройках профиля.
    'nsfw'      => false,
];
