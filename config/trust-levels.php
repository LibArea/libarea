<?php
/*
 * Настройка уровней доверия пользователей (TL) и ограничений
 * Configuring User Trust Levels (TL) and Restrictions
 */

return [
    // Ограничение для TL
    // С какого уровня TL возможно создавать пространства
    // Установите на 4, если вы хотите запретить участникам создавать их
    'tl_add_space'      => 2,
    // Сколько пространств может создать участник (TL5 - 20)
    // How many spaces can a participant create (TL5 - 20)
    'tl_add_space_limit'=> 3,
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
]; 