<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Content Creation
    |--------------------------------------------------------------------------
    |
    | From what TL level is it possible to create content.
    | С какого уровня TL возможно создавать контент.
    |
    */

    'tl_add_blog'       => 1,
    'tl_add_topic'      => 10,
    'tl_add_category'   => 10, // Catalog (каталог)
    'tl_add_section'    => 10, // Service pages (служебные страницы)
    'tl_add_team'       => 2,

    'tl_add_post'       => 1,
    'tl_add_comment'    => 1,
    'tl_add_item'       => 2, // Catalog (каталог)
    'tl_add_reply'      => 2, // Catalog (каталог)

    'tl_add_poll'       => 2,

    'tl_add_pm'         => 1, // Private messages (личные сообщения)

    'tl_add_draft'      => 2, // Drafts (черновики)

    // С какого TL можно комментировать ответы в QA модели
    'tl_add_comm_qa'    => 2,
    // С какого TL можно размещать ссылки: Reddit
    'tl_add_url'        => 3,

    // Уровень TL, до которого (включая) отправка жалоб невозможна
    // TL level, up to which (including) sending complaints is not possible
    'tl_add_report'     => 2,


    /*
    |--------------------------------------------------------------------------
    | Initial limits
    |--------------------------------------------------------------------------
    |
    | Initial limits for content creation in 1 day
    | Начальные лимиты на создание контента за 1 день
    |
    */

    'perDay_blog'       => 1,
    'perDay_topic'      => 0,
    'perDay_category'   => 0, // Catalog (каталог)
    'perDay_section'    => 0, // Service pages (служебные страницы)
    'perDay_team'       => 3,

    'perDay_post'       => 3,
    'perDay_comment'    => 8,
    'perDay_item'       => 1, // Catalog (каталог)
    'perDay_reply'      => 3, // Catalog (каталог)

    'perDay_poll'       => 3,

    'perDay_pm'         => 10, // Private messages (личные сообщения)

    'perDay_report'     => 3,

    'perDay_invite'     => 5,

    'perDay_votes'      => 35,

    /*
    |--------------------------------------------------------------------------
    | Edit time
    |--------------------------------------------------------------------------
    |
    | How long can an author edit their content (30 - minutes, 0 - always)
    | Сколько времени автор может редактировать свой контент (30 - минут, 0 - всегда)
    |
    */

    'edit_time_post'    => 60,
    'edit_time_answer'  => 30,
    'edit_time_comment' => 30,
    'edit_time_item'    => 0,
    'edit_time_reply'   => 0,
    'edit_time_poll'    => 0,

    /*
    |--------------------------------------------------------------------------
    | Trigger threshold for audit
    |--------------------------------------------------------------------------
    |
    | What should be the total contribution of the participant (the sum of posts, comments and answers) to avoid an audit
    | Какой должен быть общий вклад участника (сумма постов, комментариев и ответов), чтобы избежать аудита 
    |
    */

    'total_contribution' => 3,

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

];
