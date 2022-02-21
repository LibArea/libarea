<?php
/*
 * Настройка уровней доверия пользователей (TL) и ограничений
 * Configuring User Trust Levels (TL) and Restrictions
 */

return [
    // С какого уровня TL возможно создавать темы
    // Установите на 10, если вы хотите запретить участникам создавать их
    // From what level of TL it is possible to create Topics
    // Set to 10 if you want to prevent members from creating them 
    'tl_add_topic'      => 10,
    'count_add_topic'   => 0,

    // С какого уровня TL возможно создавать блоги
    // Установите на 10, если вы хотите запретить участникам создавать их
    // From what level of TL is it possible to create blogs
    // Set to 10 if you want to prevent members from creating them 
    'tl_add_blog'       => 1,
    'count_add_blog'    => 1,
    
    // С какого уровня TL возможно добавлять сайты
    // Установите на 10, если вы хотите запретить участникам добавлять их
    // From what TL level is it possible to add sites
    // Set to 10 if you want to prevent participants from adding them
    'tl_add_site'       => 2,
    'count_add_site'    => 1,
    
    // С какого уровня TL возможно создавать категории
    // Установите на 10, если вы хотите запретить участникам создавать их
    // From what level of TL is it possible to create categories
    // Set to 10 if you want to prevent members from creating them 
    'tl_add_category'   => 10,
    'count_add_category'=> 1,


    // С какого уровня TL возможно создавать секции
    // Установите на 10, если вы хотите запретить участникам создавать их
    // From what level of TL is it possible to create sections
    // Set to 10 if you want to prevent members from creating them 
    'tl_add_section'    => 10,
    'count_add_section' => 1,

    // С какого уровня TL возможна отправка личных сообщений
    'tl_add_pm'         => 2,
    // С какого TL можно комментировать ответы в QA модели
    'tl_add_comm_qa'    => 2,
    // С какого TL можно размещать ссылки: Reddit
    'tl_add_url'        => 3,

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
    'tl_stop_report'    => 1,
    // Общее количество жалоб в сутки (общие ограничения)
    // Total number of complaints per day (general restrictions)
    'all_stop_report'   => 3,
];
