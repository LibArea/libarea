<?php

declare(strict_types=1);

use Hleb\Static\Request;

class Sorting
{
    public static function day(string $type): string
    {
		return match (Request::get('sort')->value()) {
			'TopMonth'			=> 'ORDER BY post_votes and post_date > CURDATE()-INTERVAL 1 WEEK DESC',
			'TopThreeMonths'	=> 'ORDER BY post_votes and post_date > CURDATE()-INTERVAL 3 WEEK DESC',
			'TopYear'			=> 'ORDER BY post_votes and post_date > CURDATE()-INTERVAL 12 WEEK DESC',
			'MostComments'		=> 'ORDER BY post_comments_count DESC',
			'Viewed'			=> 'ORDER BY post_hits_count DESC',
			default				=> 'ORDER BY post_top DESC, post_date DESC',
		};
    }
}
