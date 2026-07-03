<?php

declare(strict_types=1);

use Hleb\Static\Request;

class Sorting
{
    /**
     * ORDER BY часть — только сортировка
     */
    public static function day(string $type): string
    {
        return match (Request::get('sort')->value()) {
            'TopMonth', 'TopThreeMonths', 'TopYear' 
                => 'ORDER BY p.post_votes DESC, p.post_date DESC',
            'MostComments' 
                => 'ORDER BY p.post_comments_count DESC',
            'Viewed' 
                => 'ORDER BY p.post_hits_count DESC',
            default 
                => 'ORDER BY p.post_top DESC, p.post_date DESC',
        };
    }

    /**
     * Дополнительное условие WHERE для фильтрации по дате
     * (раньше было сломано внутри ORDER BY)
     */
    public static function getDateCondition(): string
    {
        return match (Request::get('sort')->value()) {
            'TopMonth'       => 'AND p.post_date > CURDATE() - INTERVAL 1 WEEK',
            'TopThreeMonths' => 'AND p.post_date > CURDATE() - INTERVAL 3 WEEK',
            'TopYear'        => 'AND p.post_date > CURDATE() - INTERVAL 12 WEEK',
            default          => '',
        };
    }
}