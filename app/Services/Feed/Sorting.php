<?php

declare(strict_types=1);

namespace App\Services\Feed;

use Hleb\Constructor\Handlers\Request;

class Sorting
{
    public static function day(string $type)
    {
        $sort = "ORDER BY post_top DESC, post_date DESC";
        
        if ($type == 'top') {

            $day = (int)Request::getGet('sort_day');

            switch ($day) {
                case 1:
                    $sort =  "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 1 WEEK DESC";
                    break;
                case 3:
                    $sort =  "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 3 WEEK DESC";
                    break;
                default:
                    $sort =  "ORDER BY post_votes DESC";
                    break;
            }
        }
        
        return $sort;
    }
}
