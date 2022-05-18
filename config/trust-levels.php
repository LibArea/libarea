<?php

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

    'tl_add_blog'       => 2,
    'tl_add_topic'      => 10,
    'tl_add_category'   => 10, // Catalog (каталог)
    'tl_add_section'    => 10, // Service pages (служебные страницы)

    'tl_add_post'       => 1,  
    'tl_add_answer'     => 1,    
    'tl_add_comment'    => 1,   
    'tl_add_item'       => 2, // Catalog (каталог)
    'tl_add_reply'      => 1, // Catalog (каталог)

    'tl_add_pm'         => 1, // Private messages (личные сообщения)


    // С какого TL можно комментировать ответы в QA модели
    'tl_add_comm_qa'    => 2,
    // С какого TL можно размещать ссылки: Reddit
    'tl_add_url'        => 3,

    // Уровень TL, до которого (включая) отправка жалоб невозможна
    // TL level, up to which (including) sending complaints is not possible
    'tl_stop_report'    => 1,
    // Общее количество жалоб в сутки (общие ограничения)
    // Total number of complaints per day (general restrictions)
    'all_stop_report'   => 3,
    
    /*
    |--------------------------------------------------------------------------
    | Content Creation
    |--------------------------------------------------------------------------
    |
    | The maximum amount of content ever. true - do not limit
    | Максимальное количество контента за всё время. true - не ограничивать
    |
    */
    
    'count_add_blog'       => 1,
    'count_add_topic'      => 0,
    'count_add_category'   => 0, // Catalog (каталог)
    'count_add_section'    => 0, // Service pages (служебные страницы)

    'count_add_post'       => true,  
    'count_add_answer'     => true,    
    'count_add_comment'    => true,   
    'count_add_item'       => true, // Catalog (каталог)
    'count_add_reply'      => true, // Catalog (каталог)

    'count_add_pm'         => true, // Private messages (личные сообщения)
    

    /*
    |--------------------------------------------------------------------------
    | Daily limit for everyone
    |--------------------------------------------------------------------------
    |
    | Limit per day for all levels of trust (except for staff)
    | Лимит за сутки для всех уровней доверия (кроме персонала)
    |
    */

    'all_limit'         => 30,

    /*
    |--------------------------------------------------------------------------
    | Limit per day for trust level 1
    |--------------------------------------------------------------------------
    |
    | Limit per day for trust level 1 (initial)
    | Лимит за сутки для уровня доверия 1 (начальный)
    |
    */
    
    'perDay_blog'       => 1,
    'perDay_topic'      => 1,
    'perDay_category'   => 1, // Catalog (каталог)
    'perDay_section'    => 1, // Service pages (служебные страницы)

    'perDay_post'       => 3,  
    'perDay_answer'     => 5,    
    'perDay_comment'    => 8,   
    'perDay_item'       => 0, // Catalog (каталог)
    'perDay_reply'      => 0, // Catalog (каталог)

    'perDay_pm'         => 0, // Private messages (личные сообщения)

];
