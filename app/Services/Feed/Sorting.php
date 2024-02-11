<?php

declare(strict_types=1);

namespace App\Services\Feed;

use Hleb\Constructor\Handlers\Request;

class Sorting
{
    public static function day(string $type)
    {
		switch (Request::getGet('sort')) {
			case 'TopMonth':
				$sort =  "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 1 WEEK DESC";
				break;
			case 'TopThreeMonths':
				$sort =  "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 3 WEEK DESC";
				break;
			case 'TopYear':
				$sort =  "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 12 WEEK DESC";
				break;
			case 'MostComments':
				$sort =  "ORDER BY post_comments_count DESC";
				break;
			case 'Viewed':
				$sort =  "ORDER BY post_hits_count DESC";
				break;
			default:
			$sort = "ORDER BY post_top DESC, post_date DESC";
		}
        
        return $sort;
    }
}
