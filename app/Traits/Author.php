<?php

declare(strict_types=1);

namespace App\Traits;

use Hleb\Static\Container;

trait Author
{
    private function selectAuthor(int $user_id, null|string $user_new): int
    {
        if (!$user_new) {
            return $user_id;
        }

        $container = Container::getContainer();
        if ($container->user()->admin()) {
            $user_new = json_decode($user_new, true);
            return (int)$user_new[0]['id'];
        }
		
        return $user_id;
    }
}
