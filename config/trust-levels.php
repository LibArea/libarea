<?php
/*
 * Настройка уровней доверия пользователей (TL) и ограничений
 * Configuring User Trust Levels (TL) and Restrictions
 */

return [
    // С какого уровня TL возможно создавать темы
    // Установите на 4, если вы хотите запретить участникам создавать их
    // From what level of TL it is possible to create Topics
    // Set to 4 if you want to prevent members from creating them 
    'tl_add_topic'      => 4,
    'count_add_topic'   => 3,

    // С какого уровня TL возможно создавать пространства
    // Установите на 4, если вы хотите запретить участникам создавать их
    // From what level of TL is it possible to create Spaces
    // Set to 4 if you want to prevent members from creating them 
    'tl_add_space'      => 1,
    'count_add_space'   => 1,

    // С какого уровня TL возможна отправка личных сообщений
    'tl_add_pm'         => 1,
    // С какого TL можно комментировать ответы в QA модели
    'tl_add_comm_qa'    => 1,
    // С какого TL можно размещать ссылки: Reddit
    'tl_add_url'        => 2,

    // Общий лимит для TL >2 в день - отдельно по постам, комментариям, ответам
    // Total limit for TL >2 per day - separately by posts, comments, responses
    'all_limit'         => 30,

    // Сколько постов в зависимости от TL можно добавлять в день
    // How many posts, depending on TL, can be added per day
    'tl_0_add_post'     => 1,
    'tl_1_add_post'     => 3,
    'tl_2_add_post'     => 5,

    // Сколько ответов в зависимости от TL можно добавлять в день
    // How many responses, depending on TL, can be added per day
    'tl_0_add_answer'   => 3,
    'tl_1_add_answer'   => 5,
    'tl_2_add_answer'   => 10,

    // Сколько комментариев в зависимости от TL можно добавлять в день
    // How many comments, depending on TL, can be added per day
    'tl_0_add_comment'  => 5,
    'tl_1_add_comment'  => 10,
    'tl_2_add_comment'  => 20,

    // Уровень TL, до которого (включая) отправка жалоб невозможна
    // TL level, up to which (including) sending complaints is not possible
    'tl_stop_report'  => 0,
    // Общее количество жалоб в сутки (общие ограничения)
    // Total number of complaints per day (general restrictions)
    'all_stop_report'  => 3,
];
